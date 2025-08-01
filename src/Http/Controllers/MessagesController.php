<?php

namespace Chatify\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\ChMessage as Message;
use App\Models\ChFavorite as Favorite;
use Chatify\Facades\ChatifyMessenger as Chatify;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Str;

class MessagesController extends Controller
{
    public function pusherAuth(Request $request): Response|string
    {
        $authData = json_encode([
            'user_id' => Auth::user()->id,
            'user_info' => ['name' => Auth::user()->name]
        ]);

        if (Auth::check()) {
            return Chatify::pusherAuth(
                $request['channel_name'],
                $request['socket_id'],
                $authData
            );
        }

        return new Response('Unauthorized', 401);
    }

    public function index($id = null): \Illuminate\View\View
    {
        $routeName = FacadesRequest::route()->getName();
        $route = in_array($routeName, ['user', config('chatify.routes.prefix')]) ? 'user' : $routeName;

        return view('Chatify::pages.app', [
            'id' => ($id == null) ? 0 : $route . '_' . $id,
            'route' => $route,
            'messengerColor' => Auth::user()->messenger_color,
            'dark_mode' => Auth::user()->dark_mode < 1 ? 'light' : 'dark',
        ]);
    }

    public function idFetchData(Request $request): \Illuminate\Http\JsonResponse
    {
        $favorite = Chatify::inFavorite($request['id']);
        $fetch = $request['type'] == 'user' ? User::where('id', $request['id'])->first() : null;

        return Response::json([
            'favorite' => $favorite,
            'fetch' => $fetch,
            'user_avatar' => asset('/storage/' . config('chatify.user_avatar.folder') . '/' . $fetch->avatar),
        ]);
    }

    public function download($fileName): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $path = storage_path() . '/app/public/' . config('chatify.attachments.folder') . '/' . $fileName;
        if (file_exists($path)) {
            return Response::download($path, $fileName);
        }
        return abort(404, "Sorry, File does not exist in our server or may have been deleted!");
    }

    public function send(Request $request): \Illuminate\Http\JsonResponse
    {
        $error = (object)['status' => 0, 'message' => null];
        $attachment = null;
        $attachment_title = null;

        if ($request->hasFile('file')) {
            $allowed_images = Chatify::getAllowedImages();
            $allowed_files = Chatify::getAllowedFiles();
            $allowed = array_merge($allowed_images, $allowed_files);

            $file = $request->file('file');
            if ($file->getSize() < 150000000) {
                if (in_array($file->getClientOriginalExtension(), $allowed)) {
                    $attachment_title = $file->getClientOriginalName();
                    $attachment = Str::uuid() . "." . $file->getClientOriginalExtension();
                    $file->storeAs("public/" . config('chatify.attachments.folder'), $attachment);
                } else {
                    $error->status = 1;
                    $error->message = "File extension not allowed!";
                }
            } else {
                $error->status = 1;
                $error->message = "File size too large!";
            }
        }

        if (!$error->status) {
            // Gunakan uniqid agar ID pesan unik
            $messageID = uniqid('msg_', true);
            Chatify::newMessage([
                'id' => $messageID,
                'type' => $request['type'],
                'from_id' => Auth::user()->id,
                'to_id' => $request['id'],
                'body' => htmlentities(trim($request['message']), ENT_QUOTES, 'UTF-8'),
                'attachment' => $attachment ? json_encode((object)[
                    'new_name' => $attachment,
                    'old_name' => htmlentities(trim($attachment_title), ENT_QUOTES, 'UTF-8'),
                ]) : null,
            ]);

            $messageData = Chatify::fetchMessage($messageID);

            Chatify::push('private-chatify', 'messaging', [
                'from_id' => Auth::user()->id,
                'to_id' => $request['id'],
                'message' => Chatify::messageCard($messageData, 'default')
            ]);
        }

        return Response::json([
            'status' => '200',
            'error' => $error,
            'message' => Chatify::messageCard(@$messageData),
            'tempID' => $request['temporaryMsgId'],
        ]);
    }

    public function fetch(Request $request): \Illuminate\Http\JsonResponse
    {
        $allMessages = null;
        $query = Chatify::fetchMessagesQuery($request['id'])->orderBy('created_at', 'asc');
        $messages = $query->get();

        if ($query->count() > 0) {
            foreach ($messages as $message) {
                $allMessages .= Chatify::messageCard(Chatify::fetchMessage($message->id));
            }

            return Response::json([
                'count' => $query->count(),
                'messages' => $allMessages,
            ]);
        }

        return Response::json([
            'count' => $query->count(),
            'messages' => '<p class="message-hint center-el"><span>Say \'hi\' and start messaging</span></p>',
        ]);
    }

    public function seen(Request $request): \Illuminate\Http\JsonResponse
    {
        $seen = Chatify::makeSeen($request['id']);
        return Response::json(['status' => $seen], 200);
    }

    public function getContacts(Request $request): \Illuminate\Http\JsonResponse
    {
        $users = Message::join('users', function ($join) {
            $join->on('ch_messages.from_id', '=', 'users.id')
                ->orOn('ch_messages.to_id', '=', 'users.id');
        })->where(function ($q) {
            $q->where('ch_messages.from_id', Auth::user()->id)
              ->orWhere('ch_messages.to_id', Auth::user()->id);
        })->orderBy('ch_messages.created_at', 'desc')->get()->unique('id');

        $contacts = '<p class="message-hint center-el"><span>Your contact list is empty</span></p>';
        $users = $users->where('id', '!=', Auth::user()->id);

        if ($users->count() > 0) {
            $contacts = '';
            foreach ($users as $user) {
                $userCollection = User::find($user->id);
                $contacts .= Chatify::getContactItem($request['messenger_id'], $userCollection);
            }
        }

        return Response::json(['contacts' => $contacts], 200);
    }

    public function updateContactItem(Request $request): \Illuminate\Http\JsonResponse
    {
        $userCollection = User::find($request['user_id']);
        $contactItem = Chatify::getContactItem($request['messenger_id'], $userCollection);

        return Response::json(['contactItem' => $contactItem], 200);
    }

    public function favorite(Request $request): \Illuminate\Http\JsonResponse
    {
        if (Chatify::inFavorite($request['user_id'])) {
            Chatify::makeInFavorite($request['user_id'], 0);
            $status = 0;
        } else {
            Chatify::makeInFavorite($request['user_id'], 1);
            $status = 1;
        }

        return Response::json(['status' => $status], 200);
    }

    public function getFavorites(Request $request): \Illuminate\Http\JsonResponse
    {
        $favoritesList = null;
        $favorites = Favorite::where('user_id', Auth::user()->id);
        foreach ($favorites->get() as $favorite) {
            $user = User::find($favorite->favorite_id);
            $favoritesList .= view('Chatify::layouts.favorite', ['user' => $user]);
        }

        return Response::json([
            'count' => $favorites->count(),
            'favorites' => $favorites->count() > 0 ? $favoritesList : 0,
        ], 200);
    }

    public function search(Request $request): \Illuminate\Http\JsonResponse
    {
        $getRecords = null;
        $input = trim(filter_var($request['input'], FILTER_SANITIZE_STRING));
        $records = User::where('name', 'LIKE', "%{$input}%");

        foreach ($records->get() as $record) {
            $getRecords .= view('Chatify::layouts.listItem', [
                'get' => 'search_item',
                'type' => 'user',
                'user' => $record,
            ])->render();
        }

        return Response::json([
            'records' => $records->count() > 0
                ? $getRecords
                : '<p class="message-hint center-el"><span>Nothing to show.</span></p>',
            'addData' => 'html'
        ], 200);
    }
}

<?php

namespace Chatify;

use App\Models\ChMessage as Message;
use App\Models\ChFavorite as Favorite;
use Pusher\Pusher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Exception;

class ChatifyMessenger
{
    public $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            config('chatify.pusher.key'),
            config('chatify.pusher.secret'),
            config('chatify.pusher.app_id'),
            config('chatify.pusher.options')
        );
    }

    public function getAllowedImages()
    {
        return config('chatify.attachments.allowed_images');
    }

    public function getAllowedFiles()
    {
        return config('chatify.attachments.allowed_files');
    }

    public function getMessengerColors()
    {
        return [
            '1' => '#2180f3',
            '2' => '#2196F3',
            '3' => '#00BCD4',
            '4' => '#3F51B5',
            '5' => '#673AB7',
            '6' => '#4CAF50',
            '7' => '#FFC107',
            '8' => '#FF9800',
            '9' => '#ff2522',
            '10' => '#9C27B0',
        ];
    }

    public function push($channel, $event, $data)
    {
        return $this->pusher->trigger($channel, $event, $data);
    }

    public function pusherAuth($channelName, $socket_id, $data = null)
    {
        return $this->pusher->socket_auth($channelName, $socket_id, $data);
    }

    public function fetchMessage($id)
    {
        $msg = Message::find($id);
        $attachment = null;
        $attachment_type = null;
        $attachment_title = null;

        if (isset($msg->attachment)) {
            $attachmentOBJ = json_decode($msg->attachment);
            $attachment = $attachmentOBJ->new_name;
            $attachment_title = htmlentities(trim($attachmentOBJ->old_name), ENT_QUOTES, 'UTF-8');

            $ext = pathinfo($attachment, PATHINFO_EXTENSION);
            $attachment_type = in_array($ext, $this->getAllowedImages()) ? 'image' : 'file';
        }

        return [
            'id' => $msg->id,
            'from_id' => $msg->from_id,
            'to_id' => $msg->to_id,
            'message' => $msg->body,
            'attachment' => [$attachment, $attachment_title, $attachment_type],
            'time' => $msg->created_at->diffForHumans(),
            'fullTime' => $msg->created_at,
            'viewType' => ($msg->from_id == Auth::id()) ? 'sender' : 'default',
            'seen' => $msg->seen,
        ];
    }

    public function messageCard($data, $viewType = null)
    {
        $data['viewType'] = $viewType ?? $data['viewType'];
        return view('Chatify::layouts.messageCard', $data)->render();
    }

    public function fetchMessagesQuery($user_id)
    {
        return Message::where(function ($query) use ($user_id) {
            $query->where('from_id', Auth::id())->where('to_id', $user_id);
        })->orWhere(function ($query) use ($user_id) {
            $query->where('from_id', $user_id)->where('to_id', Auth::id());
        });
    }

    public function newMessage($data)
    {
        return Message::create([
            'id' => $data['id'],
            'type' => $data['type'],
            'from_id' => $data['from_id'],
            'to_id' => $data['to_id'],
            'body' => $data['body'],
            'attachment' => $data['attachment'],
        ]);
    }

    public function makeSeen($user_id)
    {
        Message::where('from_id', $user_id)
            ->where('to_id', Auth::id())
            ->where('seen', 0)
            ->update(['seen' => 1]);

        return true;
    }

    public function getLastMessageQuery($user_id)
    {
        return $this->fetchMessagesQuery($user_id)->latest()->first();
    }

    public function countUnseenMessages($user_id)
    {
        return Message::where('from_id', $user_id)
            ->where('to_id', Auth::id())
            ->where('seen', 0)
            ->count();
    }

    public function getContactItem($messenger_id, $user)
    {
        $lastMessage = $this->getLastMessageQuery($user->id);
        $unseenCounter = $this->countUnseenMessages($user->id);

        return view('Chatify::layouts.listItem', [
            'get' => 'users',
            'user' => $user,
            'lastMessage' => $lastMessage,
            'unseenCounter' => $unseenCounter,
            'type' => 'user',
            'id' => $messenger_id,
        ])->render();
    }

    public function inFavorite($user_id)
    {
        return Favorite::where('user_id', Auth::id())
            ->where('favorite_id', $user_id)
            ->exists();
    }

    public function makeInFavorite($user_id, $action)
    {
        if ($action > 0) {
            return Favorite::create([
                'id' => rand(9, 99999999),
                'user_id' => Auth::id(),
                'favorite_id' => $user_id,
            ]) ? true : false;
        } else {
            return Favorite::where('user_id', Auth::id())
                ->where('favorite_id', $user_id)
                ->delete() ? true : false;
        }
    }

    public function getSharedPhotos($user_id)
    {
        $images = [];
        $msgs = $this->fetchMessagesQuery($user_id)->orderBy('created_at', 'DESC');

        foreach ($msgs->get() as $msg) {
            if ($msg->attachment) {
                $attachment = json_decode($msg->attachment);
                if (in_array(pathinfo($attachment->new_name, PATHINFO_EXTENSION), $this->getAllowedImages())) {
                    $images[] = $attachment->new_name;
                }
            }
        }

        return $images;
    }

    public function deleteConversation($user_id)
    {
        try {
            foreach ($this->fetchMessagesQuery($user_id)->get() as $msg) {
                if ($msg->attachment) {
                    $path = storage_path('app/public/' . config('chatify.attachments.folder') . '/' . json_decode($msg->attachment)->new_name);
                    if (File::exists($path)) {
                        File::delete($path);
                    }
                }
                $msg->delete();
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

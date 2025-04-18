<div class="favorite-list-item">
    <div data-id="{{ $user->id }}" data-action="0" class="avatar av-m"
        style="
            background-image: url('{{ $user->avatar
                ? asset('/storage/' . config('chatify.user_avatar.folder') . '/' . $user->avatar)
                : asset('images/default-avatar.png') }}');
        ">
    </div>
    <p title="{{ $user->name }}">
        {{ strlen($user->name) > 5 ? substr($user->name, 0, 6) . '..' : $user->name }}
    </p>
</div>

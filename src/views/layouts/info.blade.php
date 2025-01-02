        <p class="info-name">{{ config('chatify.name') }}</p>
    </div>

    {{-- Tombol Aksi --}}
    <div class="messenger-infoView-btns">
        {{-- Tombol default (jika ingin diaktifkan bisa buka komentar) --}}
        {{-- <a href="#" class="default"><i class="fas fa-camera"></i> Default</a> --}}
        <a href="javascript:void(0)" class="danger delete-conversation">
            <i class="fas fa-trash-alt"></i> Delete Conversation
        </a>
    </div>

    {{-- Shared Photos --}}
    <div class="messenger-infoView-shared">
        <p class="messenger-title">Shared Photos</p>
        <div class="shared-photos-list">
            @if(isset($sharedPhotos) && count($sharedPhotos) > 0)
                @foreach($sharedPhotos as $photo)
                    <div class="shared-photo chat-image"
                         style="background-image: url('{{ asset($photo->url) }}');"></div>
                @endforeach
            @else
                <p style="text-align: center; color: #aaa;">No photos shared yet.</p>
            @endif
        </div>
    </div>
</div>

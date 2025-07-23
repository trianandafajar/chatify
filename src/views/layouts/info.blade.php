{{-- -------------------- User Info and Avatar -------------------- --}}
<div class="messenger-infoView">
    {{-- Avatar dan Nama --}}
    <div class="messenger-infoView-header">
        <div class="avatar av-l" style="background-image: url('{{ asset('/storage/' . config('chatify.user_avatar.folder') . '/' . $user->avatar) }}');"></div>
        <p class="info-name">{{ config('chatify.name') }}</p>
    </div>

    {{-- Tombol Aksi --}}
    <div class="messenger-infoView-btns">
        {{-- Tombol default (jika ingin diaktifkan bisa buka komentar) --}}
        {{-- <a href="#" class="default"><i class="fas fa-camera"></i> Default</a> --}}
        <a href="javascript:void(0)" class="danger delete-conversation" onclick="openModal('confirmDeleteModal')">
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

{{-- Modal konfirmasi hapus percakapan --}}
<x-modal id="confirmDeleteModal" style="display:none;" data-name="confirm-delete">
    <x-slot name="header">Konfirmasi Hapus</x-slot>
    <x-slot name="body">Apakah kamu yakin ingin menghapus percakapan ini?</x-slot>
    <x-slot name="footer">
        <button class="app-btn cancel" onclick="closeModal('confirmDeleteModal')">Batal</button>
        <button class="app-btn a-btn-danger" onclick="deleteConversation()">Hapus</button>
    </x-slot>
</x-modal>

{{-- Script open/close modal sederhana --}}
<script>
function openModal(id) {
    document.getElementById(id).style.display = 'block';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
function deleteConversation() {
    // TODO: Implementasi aksi hapus percakapan
    alert('Percakapan dihapus (dummy)!');
    closeModal('confirmDeleteModal');
}
</script>

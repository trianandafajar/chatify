{{-- ---------------------- Image modal box ---------------------- --}}
<x-image-modal id="imageModalBox" style="display:none;">
    <x-slot name="body">
        <span class="imageModal-close">&times;</span>
        <img class="imageModal-content" id="imageModalBoxSrc">
    </x-slot>
</x-image-modal>

{{-- ---------------------- Delete Modal ---------------------- --}}
<x-confirm-modal id="deleteModal" data-name="delete" style="display:none;">
    <x-slot name="header">Are you sure you want to delete this?</x-slot>
    <x-slot name="body">You can not undo this action</x-slot>
    <x-slot name="footer">
        <a href="javascript:void(0)" class="app-btn cancel" onclick="closeModal('deleteModal')">Cancel</a>
        <a href="javascript:void(0)" class="app-btn a-btn-danger delete">Delete</a>
    </x-slot>
</x-confirm-modal>

{{-- ---------------------- Alert Modal ---------------------- --}}
<x-alert-modal id="alertModal" data-name="alert" style="display:none;">
    <x-slot name="body"></x-slot>
    <x-slot name="footer">
        <a href="javascript:void(0)" class="app-btn cancel" onclick="closeModal('alertModal')">Cancel</a>
    </x-slot>
</x-alert-modal>

{{-- ---------------------- Settings Modal ---------------------- --}}
<x-confirm-modal id="settingsModal" data-name="settings" style="display:none;">
    <x-slot name="header">Update your profile settings</x-slot>
    <x-slot name="body">
        <form id="update-settings" action="{{ route('avatar.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="app-modal-body">
                {{-- Update avatar --}}
                <div class="avatar av-l upload-avatar-preview"
                     style="background-image: url('{{ asset('/storage/' . config('chatify.user_avatar.folder') . '/' . Auth::user()->avatar) }}');">
                </div>
                <p class="upload-avatar-details"></p>
                <label class="app-btn a-btn-primary update">
                    Upload profile photo
                    <input class="upload-avatar" type="file" name="avatar" accept="image/*" style="display: none;" />
                </label>

                <p class="divider"></p>

                {{-- Dark mode toggle --}}
                <p class="app-modal-header">
                    Dark Mode
                    <span class="{{ Auth::user()->dark_mode ? 'fas' : 'far' }} fa-moon dark-mode-switch"
                          data-mode="{{ Auth::user()->dark_mode }}"></span>
                </p>

                <p class="divider"></p>

                {{-- Messenger color --}}
                <p class="app-modal-header">Change {{ config('chatify.name') }} Color</p>
                <div class="update-messengerColor">
                    @for ($i = 1; $i <= 10; $i++)
                        <span class="messengerColor-{{ $i }} color-btn"></span>
                        @if ($i == 5) <br /> @endif
                    @endfor
                </div>
            </div>
        </form>
    </x-slot>
    <x-slot name="footer">
        <a href="javascript:void(0)" class="app-btn cancel" onclick="closeModal('settingsModal')">Cancel</a>
        <input type="submit" class="app-btn a-btn-success update" value="Update" form="update-settings" />
    </x-slot>
</x-confirm-modal>

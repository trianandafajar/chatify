{{-- ---------------------- Image modal box ---------------------- --}}
<div id="imageModalBox" class="imageModal">
    <span class="imageModal-close">&times;</span>
    <img class="imageModal-content" id="imageModalBoxSrc">
</div>

{{-- ---------------------- Delete Modal ---------------------- --}}
<div class="app-modal" data-name="delete">
    <div class="app-modal-container">
        <div class="app-modal-card" data-name="delete" data-modal="0">
            <div class="app-modal-header">Are you sure you want to delete this?</div>
            <div class="app-modal-body">You can not undo this action</div>
            <div class="app-modal-footer">
                <a href="javascript:void(0)" class="app-btn cancel">Cancel</a>
                <a href="javascript:void(0)" class="app-btn a-btn-danger delete">Delete</a>
            </div>
        </div>
    </div>
</div>

{{-- ---------------------- Alert Modal ---------------------- --}}
<div class="app-modal" data-name="alert">
    <div class="app-modal-container">
        <div class="app-modal-card" data-name="alert" data-modal="0">
            <div class="app-modal-header"></div>
            <div class="app-modal-body"></div>
            <div class="app-modal-footer">
                <a href="javascript:void(0)" class="app-btn cancel">Cancel</a>
            </div>
        </div>
    </div>
</div>

{{-- ---------------------- Settings Modal ---------------------- --}}
<div class="app-modal" data-name="settings">
    <div class="app-modal-container">
        <div class="app-modal-card" data-name="settings" data-modal="0">
            <form id="update-settings" action="{{ route('avatar.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="app-modal-header">Update your profile settings</div>
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

                <div class="app-modal-footer">
                    <a href="javascript:void(0)" class="app-btn cancel">Cancel</a>
                    <input type="submit" class="app-btn a-btn-success update" value="Update" />
                </div>
            </form>
        </div>
    </div>
</div>

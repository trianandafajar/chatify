@include('Chatify::layouts.headLinks')
<div class="messenger">
    {{-- ----------------------Users/Groups lists side---------------------- --}}
    <div class="messenger-listView">
        {{-- Header and search bar --}}
        <div class="m-header">
            <nav>
                <a href="#"><i class="fas fa-inbox"></i> <span class="messenger-headTitle">Messege</span> </a>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#"><i class="fas fa-cog settings-btn"></i></a>
                    <a href="#" class="listView-x"><i class="fas fa-times"></i></a>
                </nav>
            </nav>
            {{-- Search input --}}
            <input type="text" class="messenger-search" placeholder="Search" />
            {{-- Tabs --}}
            <div class="messenger-listView-tabs">
                <a href="#" @if ($route == 'user') class="active-tab" @endif data-view="users">
                    <span class="far fa-user"></span> People</a>
                <a href="#" @if ($route == 'group') class="active-tab" @endif data-view="groups">
                    <span class="fas fa-users"></span> Groups</a>
            </div>
        </div>
        {{-- tabs and lists --}}
        <div class="m-body">
            {{-- Lists [Users/Group] --}}
            {{-- ---------------- [ User Tab ] ---------------- --}}
            <div class="@if ($route == 'user') show @endif messenger-tab app-scroll" data-view="users">

                {{-- Favorites --}}
                <div class="favorites-section">
                    <p class="messenger-title">Favorites</p>
                    <div class="messenger-favorites app-scroll-thin"></div>
                </div>

                {{-- Saved Messages --}}
                {!! view('Chatify::layouts.listItem', ['get' => 'saved', 'id' => $id])->render() !!}

                {{-- Contact --}}
                <div class="listOfContacts" style="width: 100%;height: calc(100% - 200px);position: relative;"></div>

            </div>

            {{-- ---------------- [ Group Tab ] ---------------- --}}
            <div class="@if ($route == 'group') show @endif messenger-tab app-scroll" data-view="groups">
                {{-- items --}}
                <p style="text-align: center;color:grey;">Soon will be available</p>
            </div>

            {{-- ---------------- [ Search Tab ] ---------------- --}}
            <div class="messenger-tab app-scroll" data-view="search">
                {{-- items --}}
                <p class="messenger-title">Search</p>
                <div class="search-records">
                    <p class="message-hint center-el"><span>Type to search..</span></p>
                </div>
            </div>
        </div>
    </div>

    {{-- ----------------------Messaging side---------------------- --}}
    <div class="messenger-messagingView">
        {{-- header title [conversation name] amd buttons --}}
        <div class="m-header m-header-messaging">
            <nav>
                {{-- header back button, avatar and user name --}}
                <div style="display: inline-flex;">
                    <a href="#" class="show-listView"><i class="fas fa-arrow-left"></i></a>
                    <div class="avatar av-s header-avatar"
                        style="margin: 0px 10px; margin-top: -5px; margin-bottom: -5px;">
                    </div>
                    <a href="#" class="user-name">{{ config('chatify.name') }}</a>
                </div>
                {{-- header buttons --}}
                <nav class="m-header-right">
                    <a href="#" class="add-to-favorite"><i class="fas fa-star"></i></a>
                    <a href="/"><i class="fas fa-home"></i></a>
                    <a href="#" class="show-infoSide"><i class="fas fa-info-circle"></i></a>
                </nav>
            </nav>
        </div>
        {{-- Internet connection --}}
        <div class="internet-connection">
            <span class="ic-connected">Connected</span>
            <span class="ic-connecting">Connecting...</span>
            <span class="ic-noInternet">No internet access</span>
        </div>
        {{-- Messaging area --}}
        <div class="m-body app-scroll">
            <div class="messages">
                <p class="message-hint center-el"><span>Please select a chat to start messaging</span></p>
            </div>
            {{-- Typing indicator --}}
            <div class="typing-indicator">
                <div class="message-card typing">
                    <p>
                        <span class="typing-dots">
                            <span class="dot dot-1"></span>
                            <span class="dot dot-2"></span>
                            <span class="dot dot-3"></span>
                        </span>
                    </p>
                </div>
            </div>
            {{-- Send Message Form --}}
            @include('Chatify::layouts.sendForm')
        </div>
    </div>
    {{-- ---------------------- Info side ---------------------- --}}
    <div class="messenger-infoView app-scroll">
        {{-- nav actions --}}
        <nav>
            <a href="#"><i class="fas fa-times"></i></a>
        </nav>
        {!! view('Chatify::layouts.info')->render() !!}
    </div>
</div>

@include('Chatify::layouts.modals')
@include('Chatify::layouts.footerLinks')

{{-- Modal Sukses --}}
<x-success-modal id="successModal">
    <x-slot name="body">Aksi berhasil dilakukan!</x-slot>
    <x-slot name="footer">
        <button class="app-btn cancel" onclick="closeModal('successModal')">Tutup</button>
    </x-slot>
</x-success-modal>

{{-- Modal Loading --}}
<x-loading-modal id="loadingModal">
    <x-slot name="body">
        <div class="spinner"></div>
        <p>Loading...</p>
    </x-slot>
</x-loading-modal>

{{-- Modal Form --}}
<x-form-modal id="formModal">
    <x-slot name="header">Form Input</x-slot>
    <x-slot name="body">
        <form id="demoForm">
            <input type="text" name="nama" placeholder="Nama" class="app-input" />
        </form>
    </x-slot>
    <x-slot name="footer">
        <button class="app-btn cancel" onclick="closeModal('formModal')">Batal</button>
        <button class="app-btn a-btn-success" type="submit" form="demoForm">Simpan</button>
    </x-slot>
</x-form-modal>

{{-- Modal Notifikasi --}}
<x-notification-modal id="notifModal">
    <x-slot name="body">Ini adalah pesan notifikasi!</x-slot>
    <x-slot name="footer">
        <button class="app-btn cancel" onclick="closeModal('notifModal')">Tutup</button>
    </x-slot>
</x-notification-modal>

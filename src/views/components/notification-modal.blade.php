{{--
    Komponen Modal Notifikasi
    Cara pakai:
    <x-notification-modal id="notifModal">
        <x-slot name="body">Pesan notifikasi di sini</x-slot>
        <x-slot name="footer">
            <button class="app-btn cancel" onclick="closeModal('notifModal')">Tutup</button>
        </x-slot>
    </x-notification-modal>
--}}
<div {{ $attributes->merge(['class' => 'app-modal app-modal-notification']) }}>
    <div class="app-modal-container">
        <div class="app-modal-card" {{ $attributes->only(['id', 'data-name', 'data-modal']) }}>
            @isset($body)
                <div class="app-modal-body">{{ $body }}</div>
            @endisset
            @isset($footer)
                <div class="app-modal-footer">{{ $footer }}</div>
            @endisset
        </div>
    </div>
</div> 
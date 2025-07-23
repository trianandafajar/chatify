{{--
    Komponen Modal Alert
    Cara pakai:
    <x-alert-modal id="alertModal">
        <x-slot name="body">Pesan alert di sini</x-slot>
        <x-slot name="footer">
            <button class="app-btn cancel" onclick="closeModal('alertModal')">Tutup</button>
        </x-slot>
    </x-alert-modal>
--}}
<div {{ $attributes->merge(['class' => 'app-modal']) }}>
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
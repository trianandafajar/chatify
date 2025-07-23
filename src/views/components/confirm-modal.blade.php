{{--
    Komponen Modal Konfirmasi
    Cara pakai:
    <x-confirm-modal id="confirmModal">
        <x-slot name="header">Konfirmasi</x-slot>
        <x-slot name="body">Yakin ingin melakukan aksi ini?</x-slot>
        <x-slot name="footer">
            <button class="app-btn cancel" onclick="closeModal('confirmModal')">Batal</button>
            <button class="app-btn a-btn-danger">Ya</button>
        </x-slot>
    </x-confirm-modal>
--}}
<div {{ $attributes->merge(['class' => 'app-modal']) }}>
    <div class="app-modal-container">
        <div class="app-modal-card" {{ $attributes->only(['id', 'data-name', 'data-modal']) }}>
            @isset($header)
                <div class="app-modal-header">{{ $header }}</div>
            @endisset
            @isset($body)
                <div class="app-modal-body">{{ $body }}</div>
            @endisset
            @isset($footer)
                <div class="app-modal-footer">{{ $footer }}</div>
            @endisset
        </div>
    </div>
</div> 
{{--
    Komponen Modal Sukses
    Cara pakai:
    <x-success-modal id="successModal">
        <x-slot name="body">Aksi berhasil dilakukan!</x-slot>
        <x-slot name="footer">
            <button class="app-btn cancel" onclick="closeModal('successModal')">Tutup</button>
        </x-slot>
    </x-success-modal>
--}}
<div {{ $attributes->merge(['class' => 'app-modal app-modal-success']) }}>
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
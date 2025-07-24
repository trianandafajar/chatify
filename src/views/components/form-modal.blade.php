{{--
    Komponen Modal Form
    Cara pakai:
    <x-form-modal id="formModal">
        <x-slot name="header">Form Input</x-slot>
        <x-slot name="body">
            <form>...</form>
        </x-slot>
        <x-slot name="footer">
            <button class="app-btn cancel" onclick="closeModal('formModal')">Batal</button>
            <button class="app-btn a-btn-success" type="submit">Simpan</button>
        </x-slot>
    </x-form-modal>
--}}
<div {{ $attributes->merge(['class' => 'app-modal app-modal-form']) }}>
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
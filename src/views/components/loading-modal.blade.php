{{--
    Komponen Modal Loading
    Cara pakai:
    <x-loading-modal id="loadingModal">
        <x-slot name="body">
            <div class="spinner"></div>
            <p>Loading...</p>
        </x-slot>
    </x-loading-modal>
--}}
<div {{ $attributes->merge(['class' => 'app-modal app-modal-loading']) }}>
    <div class="app-modal-container">
        <div class="app-modal-card" {{ $attributes->only(['id', 'data-name', 'data-modal']) }}>
            @isset($body)
                <div class="app-modal-body">{{ $body }}</div>
            @endisset
        </div>
    </div>
</div> 
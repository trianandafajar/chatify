{{--
    Komponen Modal Gambar (Preview)
    Cara pakai:
    <x-image-modal id="imageModal">
        <x-slot name="body">
            <img src="..." alt="Preview" style="max-width:100%" />
        </x-slot>
    </x-image-modal>
--}}
<div {{ $attributes->merge(['class' => 'imageModal']) }}>
    @isset($body)
        {{ $body }}
    @endisset
    {{ $slot }}
</div> 
{{--
    Komponen Modal Reusable
    Cara pakai:
    <x-modal id="myModal" data-name="custom">
        <x-slot name="header">Judul Modal</x-slot>
        <x-slot name="body">Isi konten modal</x-slot>
        <x-slot name="footer">Tombol aksi</x-slot>
        <!-- Atau masukkan konten custom langsung di sini -->
    </x-modal>
--}}
<div {{ $attributes->merge(['class' => 'app-modal']) }}>
    <div class="app-modal-container">
        <div class="app-modal-card" {{ $attributes->only(['data-name', 'data-modal', 'id']) }}>
            @isset($header)
                <div class="app-modal-header">{{ $header }}</div>
            @endisset
            @isset($body)
                <div class="app-modal-body">{{ $body }}</div>
            @endisset
            @isset($footer)
                <div class="app-modal-footer">{{ $footer }}</div>
            @endisset
            {{-- Slot default untuk konten custom --}}
            {{ $slot }}
        </div>
    </div>
</div> 
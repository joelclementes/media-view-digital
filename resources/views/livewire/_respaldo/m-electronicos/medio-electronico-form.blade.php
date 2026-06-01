<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-medios.data-container section="sujeto">
            <livewire:persona wire:key="persona-form" />
        </x-medios.data-container>
        <x-medios.data-container section="medio">
            <livewire:medio-electronico wire:key="medio-form" />
        </x-medios.data-container>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-medios.data-container section="publicación">
            <livewire:publicacion-electronico wire:key="publicacion-form" />
        </x-medios.data-container>
        <x-medios.data-container section="autor">
            <livewire:autor-electronico wire:key="autor-form" />
        </x-medios.data-container>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-medios.data-container section="referencia">
            <livewire:referencia :mostrarDomiciliaria="false" wire:key="referencia-form" />
        </x-medios.data-container>
        <x-medios.data-container section="observacion">
            <livewire:observaciones wire:key="observaciones-form" />
        </x-medios.data-container>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <x-medios.data-container section="archivos" title="Archivos">
            <x-medios.archivos-selector :archivos="$archivos" />
        </x-medios.data-container>
    </div>

    @error('persona.sujeto_id')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('medio.url_pagina')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('publicacion.fecha')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('publicacion.tamano_id')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('publicacion.genero_id')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
    @error('autor.genero_sujeto_id')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <div class="mt-6 flex justify-end">
        <x-button type="button" wire:click="guardar" wire:loading.attr="disabled" wire:target="guardar,archivos">
            Guardar
        </x-button>
    </div>
</div>

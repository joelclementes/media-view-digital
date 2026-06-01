<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-md sm:rounded-md p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @include('livewire.medios.electronicos.partials.persona')
            @include('livewire.medios.electronicos.partials.medio')
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @include('livewire.medios.electronicos.partials.publicacion')
            @include('livewire.medios.electronicos.partials.autor')
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @include('livewire.medios.electronicos.partials.referencia')
            @include('livewire.medios.electronicos.partials.observaciones')
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @include('livewire.medios.electronicos.partials.archivos')
        </div>

        <div class="mt-6 flex justify-end gap-3">
            <button
                type="button"
                wire:click="limpiarFormulario"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
            >
                Limpiar
            </button>

            <x-button
                type="button"
                wire:click="guardar"
                wire:loading.attr="disabled"
                wire:target="guardar,archivos"
            >
                <span wire:loading.remove wire:target="guardar">Guardar registro</span>
                <span wire:loading wire:target="guardar">Guardando...</span>
            </x-button>
        </div>
    </div>
</div>
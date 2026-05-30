@props([
    'archivos' => [],
])

<div class="space-y-3">
    <div>
        <x-label for="archivos" value="Seleccionar archivo(s)" />
        <input id="archivos" type="file" wire:model.live="archivos" multiple
            class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100" />
        <p class="mt-1 text-xs text-gray-500">Puedes adjuntar uno o varios archivos (máximo 10MB por archivo).</p>
        @error('archivos')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('archivos.*')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    @if (!empty($archivos))
        <ul class="space-y-2 rounded-md border border-gray-200 bg-gray-50 p-3">
            @foreach ($archivos as $index => $archivo)
                <li class="flex items-center justify-between text-sm text-gray-700">
                    <span class="truncate pr-3">{{ $archivo->getClientOriginalName() }}</span>
                    <button type="button" wire:click="eliminarArchivo({{ $index }})"
                        class="text-xs font-medium text-red-600 hover:text-red-800">
                        Quitar
                    </button>
                </li>
            @endforeach
        </ul>
    @endif

    <div wire:loading wire:target="archivos" class="text-xs text-blue-600">Cargando archivos...</div>
</div>

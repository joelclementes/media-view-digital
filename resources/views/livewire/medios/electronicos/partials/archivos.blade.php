<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Archivos
    </h3>

    <x-label for="archivos" value="Seleccionar archivo(s)" />

    <input
        id="archivos"
        type="file"
        wire:model.live="archivos"
        multiple
        class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
    />

    <p class="mt-1 text-xs text-gray-500">
        Formatos permitidos: PDF, imágenes, Word, Excel, audio y video. Máximo 10 MB por archivo.
    </p>

    @error('archivos')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @error('archivos.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <div wire:loading wire:target="archivos" class="mt-2 text-xs text-blue-600">
        Cargando archivos...
    </div>

    @if (!empty($archivos))
        <ul class="mt-3 space-y-2 rounded-md border border-gray-200 bg-gray-50 p-3">
            @foreach ($archivos as $indice => $archivo)
                <li class="flex items-center justify-between text-sm text-gray-700">
                    <span class="truncate pr-3">
                        {{ $archivo->getClientOriginalName() }}
                    </span>

                    <button
                        type="button"
                        wire:click="eliminarArchivo({{ $indice }})"
                        class="text-xs font-medium text-red-600 hover:text-red-800"
                    >
                        Quitar
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>
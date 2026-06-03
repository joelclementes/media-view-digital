<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Archivos
    </h3>

    <x-label for="archivos" value="Seleccionar archivo(s)" />

    {{-- <input id="archivos" type="file" wire:model.live="archivos" multiple accept="image/*"
        class="mt-1 block w-full text-sm text-gray-700 file:mr-4 file:rounded-md file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100" /> --}}

    <div>
        <input id="archivos" type="file" wire:model.live="archivos" multiple accept="image/*" class="hidden" />

        <label for="archivos"
            class="inline-flex cursor-pointer items-center rounded-md bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700 hover:bg-primary-100">
            Seleccionar imágenes
        </label>

        <span class="ml-3 text-sm text-gray-500">
            JPG, JPEG o PNG
        </span>
    </div>

    <p class="mt-1 text-xs text-gray-500">
        Formatos permitidos: imágenes. Máximo 10 MB por archivo.
    </p>

    @error('archivos')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @error('archivos.*')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <div wire:loading wire:target="archivos" class="mt-2 text-xs text-primary-600">
        Cargando archivos...
    </div>

    @if (!empty($archivos_existentes))
        <div class="mt-4">
            <p class="mb-2 text-sm font-medium text-gray-700">
                Imágenes actuales
            </p>

            <ul class="space-y-2 rounded-md border border-gray-200 bg-gray-50 p-3">
                @foreach ($archivos_existentes as $indice => $archivo)
                    <li class="flex items-center justify-between gap-3 text-sm text-gray-700">
                        <a href="{{ Storage::url($archivo) }}" target="_blank"
                            class="truncate text-primary-700 hover:underline">
                            {{ basename($archivo) }}
                        </a>

                        <button type="button" wire:click="eliminarArchivoExistente({{ $indice }})"
                            class="text-xs font-medium text-red-600 hover:text-red-800">
                            Quitar
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!empty($archivos))
        <ul class="mt-3 space-y-2 rounded-md border border-gray-200 bg-gray-50 p-3">
            @foreach ($archivos as $indice => $archivo)
                <li class="flex items-center justify-between text-sm text-gray-700">
                    <span class="truncate pr-3">
                        {{ $archivo->getClientOriginalName() }}
                    </span>

                    <button type="button" wire:click="eliminarArchivo({{ $indice }})"
                        class="text-xs font-medium text-red-600 hover:text-red-800">
                        Quitar
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>

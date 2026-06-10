<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Audio</h3>

    <x-label for="archivos" value="Seleccionar audio(s)" />

    <div>
        <input id="archivos" type="file" wire:model.live="archivos" multiple accept="audio/mpeg,.mp3" class="hidden" />

        <label for="archivos"
            class="inline-flex cursor-pointer items-center rounded-md bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700 hover:bg-primary-100">
            Seleccionar MP3
        </label>

        <span class="ml-3 text-sm text-gray-500">MP3</span>
    </div>

    <p class="mt-1 text-xs text-gray-500">Formato permitido: MP3. Máximo 20 MB por archivo.</p>

    <x-input-error for="archivos" class="mt-1" />
    <x-input-error for="archivos.*" class="mt-1" />

    <div wire:loading wire:target="archivos" class="mt-2 text-xs text-primary-600">
        Cargando archivos...
    </div>

    @if (!empty($archivos_existentes))
        <div class="mt-4">
            <p class="mb-2 text-sm font-medium text-gray-700">Audios actuales</p>

            <ul class="space-y-3 rounded-md border border-gray-200 bg-gray-50 p-3">
                @foreach ($archivos_existentes as $indice => $archivo)
                    <li class="text-sm text-gray-700">
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ Storage::url($archivo) }}" target="_blank" class="truncate text-primary-700 hover:underline">
                                {{ basename($archivo) }}
                            </a>

                            <button type="button" wire:click="eliminarArchivoExistente({{ $indice }})"
                                class="text-xs font-medium text-red-600 hover:text-red-800">
                                Quitar
                            </button>
                        </div>

                        <audio controls preload="metadata" class="mt-2 w-full">
                            <source src="{{ Storage::url($archivo) }}" type="audio/mpeg">
                            Tu navegador no soporta el reproductor de audio.
                        </audio>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (!empty($archivos))
        <ul class="mt-3 space-y-2 rounded-md border border-gray-200 bg-gray-50 p-3">
            @foreach ($archivos as $indice => $archivo)
                <li class="flex items-center justify-between text-sm text-gray-700">
                    <span class="truncate pr-3">{{ $archivo->getClientOriginalName() }}</span>

                    <button type="button" wire:click="eliminarArchivo({{ $indice }})"
                        class="text-xs font-medium text-red-600 hover:text-red-800">
                        Quitar
                    </button>
                </li>
            @endforeach
        </ul>
    @endif
</div>

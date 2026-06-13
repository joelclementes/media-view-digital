<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Video</h3>

    <x-label for="archivos" value="Seleccionar video(s)" />

    <div>
        <input id="archivos" type="file" wire:model.live="archivos" multiple accept="video/mp4,video/quicktime,video/x-msvideo,video/x-ms-wmv,video/x-matroska,.mp4,.mov,.avi,.wmv,.mkv" class="hidden" />

        <label for="archivos"
            class="inline-flex cursor-pointer items-center rounded-md bg-primary-50 px-4 py-2 text-sm font-semibold text-primary-700 hover:bg-primary-100">
            Seleccionar video
        </label>

        <span class="ml-3 text-sm text-gray-500">MP4, MOV, AVI, WMV, MKV</span>
    </div>

    <p class="mt-1 text-xs text-gray-500">Formatos permitidos: MP4, MOV, AVI, WMV, MKV. Máximo 50 MB por archivo.</p>

    <x-input-error for="archivos" class="mt-1" />
    <x-input-error for="archivos.*" class="mt-1" />

    <div wire:loading wire:target="archivos" class="mt-2 text-xs text-primary-600">
        Cargando archivos...
    </div>

    @if (!empty($archivos_existentes))
        <div class="mt-4">
            <p class="mb-2 text-sm font-medium text-gray-700">Videos actuales</p>

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

                        <video controls preload="metadata" class="mt-2 w-full rounded-lg">
                            <source src="{{ Storage::url($archivo) }}">
                            Tu navegador no soporta el reproductor de video.
                        </video>
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

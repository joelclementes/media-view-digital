<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Datos de la publicación</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-label for="publicacion_tipo_id" value="Tipo de publicidad" />
            <select id="publicacion_tipo_id" wire:model.live="publicacion_tipo_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar tipo</option>
                @foreach ($tipos_publicidad as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="publicacion_tipo_id" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_medidas" value="Medidas" />
            <x-input id="publicacion_medidas" type="text" wire:model.live.debounce.500ms="publicacion_medidas" placeholder="Ej. 2 x 3 m" class="w-full" />
            <x-input-error for="publicacion_medidas" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_version" value="Versión" />
            <x-input id="publicacion_version" type="text" wire:model.live.debounce.500ms="publicacion_version" class="w-full" />
            <x-input-error for="publicacion_version" class="mt-1" />
        </div>
    </div>
</div>

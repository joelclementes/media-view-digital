<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Datos de la publicación</h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="publicacion_fecha" value="Fecha de publicación" />
            <x-input id="publicacion_fecha" type="date" wire:model.live="publicacion_fecha" class="w-full" max="{{ now()->format('Y-m-d') }}" />
            <x-input-error for="publicacion_fecha" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_hora" value="Hora de publicación" />
            <x-input id="publicacion_hora" type="time" wire:model.live="publicacion_hora" class="w-full" />
            <x-input-error for="publicacion_hora" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_tiempo" value="Tiempo en segundos" />
            <x-input id="publicacion_tiempo" type="number" min="1" wire:model.live="publicacion_tiempo" class="w-full" />
            <x-input-error for="publicacion_tiempo" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_tipo" value="Tipo de publicación" />
            <select id="publicacion_tipo" wire:model.live="publicacion_tipo"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar tipo</option>
                @foreach ($tipos_publicacion as $tipo)
                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                @endforeach
            </select>
            <x-input-error for="publicacion_tipo" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_ubicacion" value="Ubicación" />
            <select id="publicacion_ubicacion" wire:model.live="publicacion_ubicacion"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar ubicación</option>
                @foreach ($ubicaciones_publicacion as $ubicacion)
                    <option value="{{ $ubicacion }}">{{ $ubicacion }}</option>
                @endforeach
            </select>
            <x-input-error for="publicacion_ubicacion" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_modalidad" value="Modalidad" />
            <select id="publicacion_modalidad" wire:model.live="publicacion_modalidad"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar modalidad</option>
                @foreach ($modalidades_publicacion as $modalidad)
                    <option value="{{ $modalidad }}">{{ $modalidad }}</option>
                @endforeach
            </select>
            <x-input-error for="publicacion_modalidad" class="mt-1" />
        </div>
    </div>
</div>

<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Datos de la publicación
    </h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="publicacion_fecha" value="Fecha de publicación" />
            <x-input id="publicacion_fecha" type="date" wire:model.live="publicacion_fecha"
                class="w-full" max="{{ now()->format('Y-m-d') }}" />
            <x-input-error for="publicacion_fecha" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_lugar" value="Lugar de publicación" />
            <select id="publicacion_lugar" wire:model.live="publicacion_lugar"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar lugar</option>
                <option value="Superior">Superior</option>
                <option value="Inferior">Inferior</option>
                <option value="Centro">Centro</option>
                <option value="Derecha">Derecha</option>
                <option value="Izquierda">Izquierda</option>
            </select>
            <x-input-error for="publicacion_lugar" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_tamano_id" value="Tamaño de publicación" />
            <select id="publicacion_tamano_id" wire:model.live="publicacion_tamano_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar tamaño</option>
                @foreach ($tamanos as $tamano)
                    <option value="{{ $tamano->id }}">{{ $tamano->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="publicacion_tamano_id" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_genero_id" value="Género periodístico" />
            <select id="publicacion_genero_id" wire:model.live="publicacion_genero_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar género</option>
                @foreach ($generos as $genero)
                    <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="publicacion_genero_id" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_seccion" value="Sección" />
            <x-input id="publicacion_seccion" type="text" wire:model.live="publicacion_seccion"
                class="w-full" />
            <x-input-error for="publicacion_seccion" class="mt-1" />
        </div>

        <div>
            <x-label for="publicacion_pagina" value="Página" />
            <x-input id="publicacion_pagina" type="text" wire:model.live="publicacion_pagina"
                class="w-full" />
            <x-input-error for="publicacion_pagina" class="mt-1" />
        </div>
    </div>
</div>
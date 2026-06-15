<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Medio cine</h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="medio_cine_id" value="Cine" />
            <select id="medio_cine_id" wire:model.live="medio_cine_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar cine</option>
                @foreach ($cines as $cine)
                    <option value="{{ $cine->id }}">{{ $cine->nombre }}{{ $cine->ciudad ? ' - ' . $cine->ciudad : '' }}</option>
                @endforeach
            </select>
            <x-input-error for="medio_cine_id" class="mt-1" />
        </div>

        <div>
            <x-label for="medio_distrito_id" value="Distrito" />
            <select id="medio_distrito_id" wire:model.live="medio_distrito_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar distrito</option>
                @foreach ($distritos as $distrito)
                    <option value="{{ $distrito->id }}">{{ $distrito->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="medio_distrito_id" class="mt-1" />
        </div>

        <div>
            <x-label for="medio_municipio_id" value="Municipio" />
            <select id="medio_municipio_id" wire:model.live="medio_municipio_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar municipio</option>
                @foreach ($municipios as $municipio)
                    <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="medio_municipio_id" class="mt-1" />
        </div>

        <div>
            <x-label for="medio_localidad_id" value="Localidad" />
            <select id="medio_localidad_id" wire:model.live="medio_localidad_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                @disabled(!$medio_municipio_id)>
                <option value="">{{ $medio_municipio_id ? 'Seleccionar localidad' : 'Primero selecciona municipio' }}</option>
                @foreach ($localidades as $localidad)
                    <option value="{{ $localidad->id }}">{{ $localidad->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="medio_localidad_id" class="mt-1" />
        </div>

        <div>
            <x-label for="medio_sala" value="Sala" />
            <x-input id="medio_sala" type="text" wire:model.live.debounce.500ms="medio_sala" class="w-full" placeholder="Sala, si aplica" />
            <x-input-error for="medio_sala" class="mt-1" />
        </div>
    </div>
</div>

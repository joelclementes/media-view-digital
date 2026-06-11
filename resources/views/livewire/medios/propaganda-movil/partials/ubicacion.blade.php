<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Ubicación y datos del móvil</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <x-label for="razon_social" value="Razón social" />
            <x-input id="razon_social" type="text" wire:model.live.debounce.500ms="razon_social" class="w-full" />
            <x-input-error for="razon_social" class="mt-1" />
        </div>

        <div>
            <x-label for="distrito_id" value="Distrito" />
            <select id="distrito_id" wire:model.live="distrito_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar distrito</option>
                @foreach ($distritos as $distrito)
                    <option value="{{ $distrito->id }}">{{ $distrito->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="distrito_id" class="mt-1" />
        </div>

        <div>
            <x-label for="municipio_id" value="Municipio" />
            <select id="municipio_id" wire:model.live="municipio_id" @disabled(empty($distrito_id)) class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                <option value="">{{ empty($distrito_id) ? 'Primero selecciona distrito' : 'Seleccionar municipio' }}</option>
                @foreach ($municipios as $municipio)
                    <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="municipio_id" class="mt-1" />
        </div>

        <div>
            <x-label for="localidad_id" value="Localidad" />
            <select id="localidad_id" wire:model.live="localidad_id" @disabled(empty($municipio_id)) class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 disabled:bg-gray-100 disabled:cursor-not-allowed">
                <option value="">{{ empty($municipio_id) ? 'Primero selecciona municipio' : 'Seleccionar localidad' }}</option>
                @foreach ($localidades as $localidad)
                    <option value="{{ $localidad->id }}">{{ $localidad->nombre }}</option>
                @endforeach
            </select>
            <x-input-error for="localidad_id" class="mt-1" />
        </div>

        <div>
            <x-label for="seccion" value="Sección" />
            <x-input id="seccion" type="text" wire:model.live.debounce.500ms="seccion" class="w-full" />
            <x-input-error for="seccion" class="mt-1" />
        </div>

        <div>
            <x-label for="latitud" value="Latitud" />
            <x-input id="latitud" type="number" step="0.000000000000001" wire:model.live.debounce.500ms="latitud" class="w-full" />
            <x-input-error for="latitud" class="mt-1" />
        </div>

        <div>
            <x-label for="longitud" value="Longitud" />
            <x-input id="longitud" type="number" step="0.000000000000001" wire:model.live.debounce.500ms="longitud" class="w-full" />
            <x-input-error for="longitud" class="mt-1" />
        </div>

        <div>
            <x-label for="unidad" value="Unidad de transporte" />
            <x-input id="unidad" type="text" wire:model.live.debounce.500ms="unidad" class="w-full" />
            <x-input-error for="unidad" class="mt-1" />
        </div>

        <div>
            <x-label for="numero" value="Número económico" />
            <x-input id="numero" type="text" wire:model.live.debounce.500ms="numero" class="w-full" />
            <x-input-error for="numero" class="mt-1" />
        </div>

        <div>
            <x-label for="placa" value="Número de placa del móvil" />
            <x-input id="placa" type="text" wire:model.live.debounce.500ms="placa" class="w-full" />
            <x-input-error for="placa" class="mt-1" />
        </div>

        <div class="md:col-span-2">
            <x-label for="vialidad" value="Vialidad" />
            <x-input id="vialidad" type="text" wire:model.live.debounce.500ms="vialidad" placeholder="Calle, avenida, camino, etc." class="w-full" />
            <x-input-error for="vialidad" class="mt-1" />
        </div>
    </div>
</div>

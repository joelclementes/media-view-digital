<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">Medio radio</h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="medio_nombre" value="Nombre del medio" />
            <x-input id="medio_nombre" type="text" wire:model.live.debounce.500ms="medio_nombre" class="w-full" />
            <x-input-error for="medio_nombre" class="mt-1" />
        </div>

        <div>
            <x-label for="medio_siglas" value="Siglas" />
            <x-input id="medio_siglas" type="text" wire:model.live.debounce.500ms="medio_siglas" class="w-full" />
            <x-input-error for="medio_siglas" class="mt-1" />
        </div>

        <div>
            <x-label for="medio_banda" value="Banda" />
            <x-input id="medio_banda" type="text" wire:model.live.debounce.500ms="medio_banda" placeholder="Ej. AM, FM, 90.5 FM" class="w-full" />
            <x-input-error for="medio_banda" class="mt-1" />
        </div>

        <div>
            <x-label for="medio_grupo_radiofonico" value="Grupo radiofónico" />
            <x-input id="medio_grupo_radiofonico" type="text" wire:model.live.debounce.500ms="medio_grupo_radiofonico" class="w-full" />
            <x-input-error for="medio_grupo_radiofonico" class="mt-1" />
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
            <x-label for="medio_cobertura" value="Cobertura" />
            <select id="medio_cobertura" wire:model.live="medio_cobertura"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="">Seleccionar cobertura</option>
                @foreach ($coberturas as $cobertura)
                    <option value="{{ $cobertura }}">{{ $cobertura }}</option>
                @endforeach
            </select>
            <x-input-error for="medio_cobertura" class="mt-1" />
        </div>
    </div>
</div>

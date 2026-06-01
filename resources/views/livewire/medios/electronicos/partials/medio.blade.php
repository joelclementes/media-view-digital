<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Medio electrónico
    </h3>

    <div class="grid grid-cols-1 gap-4">
        <div>
            <x-label for="selector_portal" value="Página electrónica" />

            <select
                id="selector_portal"
                wire:model.live="selector_portal"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Seleccionar portal</option>

                @foreach ($portales as $portal)
                    <option value="{{ $portal->id }}">
                        {{ $portal->nombre }} - {{ $portal->ciudad }} ({{ $portal->tipo }})
                    </option>
                @endforeach

                <option value="otro">+ Agregar otro portal</option>
            </select>

            @error('portal_internet_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="url_pagina" value="URL de la publicación" />

            <x-input
                id="url_pagina"
                type="url"
                wire:model.live.debounce.500ms="url_pagina"
                placeholder="https://ejemplo.com/noticia"
                class="w-full"
            />

            @error('url_pagina')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    @if ($mostrar_formulario_portal)
        <div class="mt-4 p-4 bg-gray-50 rounded-lg border">
            <h4 class="text-md font-medium mb-3">
                Agregar nuevo portal
            </h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="nuevo_portal_nombre" value="Nombre del portal" />
                    <x-input
                        id="nuevo_portal_nombre"
                        type="text"
                        wire:model.live="nuevo_portal.nombre"
                        placeholder="Ej: El Universal"
                        class="w-full"
                    />

                    @error('nuevo_portal.nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-label for="nuevo_portal_url" value="URL del portal" />
                    <x-input
                        id="nuevo_portal_url"
                        type="url"
                        wire:model.live="nuevo_portal.url"
                        placeholder="https://ejemplo.com"
                        class="w-full"
                    />

                    @error('nuevo_portal.url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-label for="nuevo_portal_ciudad" value="Ciudad" />
                    <x-input
                        id="nuevo_portal_ciudad"
                        type="text"
                        wire:model.live="nuevo_portal.ciudad"
                        placeholder="Ej: Xalapa"
                        class="w-full"
                    />

                    @error('nuevo_portal.ciudad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-label for="nuevo_portal_tipo" value="Tipo" />

                    <select
                        id="nuevo_portal_tipo"
                        wire:model.live="nuevo_portal.tipo"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Seleccionar tipo</option>
                        <option value="Periódico">Periódico</option>
                        <option value="Revista">Revista</option>
                        <option value="Portal de noticias">Portal de noticias</option>
                        <option value="Blog">Blog</option>
                        <option value="Agencia">Agencia</option>
                        <option value="Otro">Otro</option>
                    </select>

                    @error('nuevo_portal.tipo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <button
                    type="button"
                    wire:click="cancelarNuevoPortal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >
                    Cancelar
                </button>

                <button
                    type="button"
                    wire:click="guardarNuevoPortal"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700"
                >
                    Guardar portal
                </button>
            </div>
        </div>
    @endif
</div>
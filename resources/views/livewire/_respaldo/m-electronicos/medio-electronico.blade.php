<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
            </path>
        </svg>
        Medio Electrónico
    </h3>

    <div class="grid grid-cols-1 gap-4">
        {{-- Select de Portal --}}
        <div>
            <x-label for="portal_internet_id" value="Página electrónica" />
            <select id="portal_internet_id" wire:model.live="portal_internet_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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

        {{-- URL de la página --}}
        <div>
            <x-label for="url_pagina" value="URL de la página" />
            <x-input id="url_pagina" type="url" wire:model.live="url_pagina"
                placeholder="https://ejemplo.com/noticia" class="w-full" />
            @error('url_pagina')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">URL completa de la publicación</p>
        </div>
    </div>

    {{-- Formulario para nuevo portal --}}
    @if ($mostrarOtroPortal)
        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
            <h4 class="text-md font-medium mb-3">Agregar nuevo portal</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Nombre del portal --}}
                <div>
                    <x-label for="nuevoPortal.nombre" value="Nombre del portal" />
                    <x-input id="nuevoPortal.nombre" type="text" wire:model="nuevoPortal.nombre"
                        placeholder="Ej: El Universal" class="w-full" />
                    @error('nuevoPortal.nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- URL del portal --}}
                <div>
                    <x-label for="nuevoPortal.url" value="URL del portal" />
                    <x-input id="nuevoPortal.url" type="url" wire:model="nuevoPortal.url"
                        placeholder="https://ejemplo.com" class="w-full" />
                    @error('nuevoPortal.url')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ciudad --}}
                <div>
                    <x-label for="nuevoPortal.ciudad" value="Ciudad" />
                    <x-input id="nuevoPortal.ciudad" type="text" wire:model="nuevoPortal.ciudad"
                        placeholder="Ej: Ciudad de México" class="w-full" />
                    @error('nuevoPortal.ciudad')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo --}}
                <div>
                    <x-label for="nuevoPortal.tipo" value="Tipo" />
                    <select id="nuevoPortal.tipo" wire:model="nuevoPortal.tipo"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Seleccionar tipo</option>
                        <option value="Periódico">Periódico</option>
                        <option value="Revista">Revista</option>
                        <option value="Portal de noticias">Portal de noticias</option>
                        <option value="Blog">Blog</option>
                        <option value="Agencia">Agencia</option>
                        <option value="Otro">Otro</option>
                    </select>
                    @error('nuevoPortal.tipo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Botones del formulario --}}
            <div class="mt-4 flex justify-end space-x-2">
                <button type="button" wire:click="cancelarNuevoPortal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </button>
                <button type="button" wire:click="guardarNuevoPortal"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Guardar portal
                </button>
            </div>
        </div>
    @endif

    {{-- Resumen de selección --}}
    {{-- @if ($portal_internet_id && !$mostrarOtroPortal)
        @php
            $portalSeleccionado = $portales->find($portal_internet_id);
        @endphp
        @if ($portalSeleccionado)
            <div class="mt-4 p-3 bg-green-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-2">Portal seleccionado:</p>
                <div class="text-sm text-gray-600">
                    <p>• <span class="font-medium">{{ $portalSeleccionado->nombre }}</span> -
                        {{ $portalSeleccionado->ciudad }}</p>
                    <p>• Tipo: {{ $portalSeleccionado->tipo }}</p>
                    @if ($url_pagina)
                        <p>• URL publicación: <a href="{{ $url_pagina }}" target="_blank"
                                class="text-blue-600 hover:underline">{{ $url_pagina }}</a></p>
                    @endif
                </div>
            </div>
        @endif
    @endif --}}
</div>

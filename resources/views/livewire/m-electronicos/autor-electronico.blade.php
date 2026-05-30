<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Autor de la Publicación
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Select Género del Autor --}}
        <div>
            <x-label for="genero_sujeto_id" value="Género del autor" />
            <select id="genero_sujeto_id" wire:model.live="genero_sujeto_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Seleccionar género</option>
                @foreach ($generosSujeto as $genero)
                    <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
                @endforeach
            </select>
            @error('genero_sujeto_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Input Nombre del Autor (opcional) --}}
        <div>
            <x-label for="nombre" value="Nombre del autor" />
            <x-input id="nombre" type="text" wire:model.live="nombre" placeholder="Nombre del autor (opcional)"
                class="w-full" />
            @error('nombre')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Campo opcional</p>
        </div>
    </div>

    {{-- Resumen de selección --}}
    {{-- @if ($genero_sujeto_id || $nombre)
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm font-medium text-gray-700 mb-2">Autor:</p>
            <div class="text-sm text-gray-600 space-y-1">
                @if ($genero_sujeto_id)
                    @php
                        $generoSeleccionado = $generosSujeto->firstWhere('id', $genero_sujeto_id);
                    @endphp
                    @if ($generoSeleccionado)
                        <p>• Género: <span class="font-medium">{{ $generoSeleccionado->nombre }}</span></p>
                    @endif
                @endif

                @if ($nombre)
                    <p>• Nombre: <span class="font-medium">{{ $nombre }}</span></p>
                @else
                    <p class="text-gray-400">• Nombre: No especificado</p>
                @endif
            </div>
        </div>
    @endif --}}

    {{-- Datos ocultos para validación --}}
    <input type="hidden" wire:model="genero_sujeto_id">
    <input type="hidden" wire:model="nombre">
</div>

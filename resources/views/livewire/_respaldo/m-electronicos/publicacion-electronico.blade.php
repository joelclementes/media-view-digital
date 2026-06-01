<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
            </path>
        </svg>
        Datos de la Publicación
    </h3>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Campo Fecha --}}
        <div>
            <x-label for="fecha" value="Fecha de publicación" />
            <x-input id="fecha" type="date" wire:model.live="fecha" class="w-full"
                max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />
            @error('fecha')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Select Tamaño --}}
        <div>
            <x-label for="tamano_id" value="Tamaño de publicación" />
            <select id="tamano_id" wire:model.live="tamano_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Seleccionar tamaño</option>
                @foreach ($tamanos as $tamano)
                    <option value="{{ $tamano->id }}">{{ $tamano->nombre }}</option>
                @endforeach
            </select>
            @error('tamano_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Select Género --}}
        <div>
            <x-label for="genero_id" value="Género periodístico" />
            <select id="genero_id" wire:model.live="genero_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <option value="">Seleccionar género</option>
                @foreach ($generos as $genero)
                    <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
                @endforeach
            </select>
            @error('genero_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Géneros para medio electrónico</p>
        </div>
    </div>

    {{-- Resumen de selección --}}
    {{-- @if ($fecha || $tamano_id || $genero_id)
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm font-medium text-gray-700 mb-2">Resumen de publicación:</p>
            <div class="text-sm text-gray-600 space-y-1">
                @if ($fecha)
                    <p>• Fecha: <span class="font-medium">{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</span>
                    </p>
                @endif

                @if ($tamano_id)
                    @php
                        $tamano = $tamanos->firstWhere('id', $tamano_id);
                    @endphp
                    @if ($tamano)
                        <p>• Tamaño: <span class="font-medium">{{ $tamano->nombre }}</span></p>
                    @endif
                @endif

                @if ($genero_id)
                    @php
                        $genero = $generos->firstWhere('id', $genero_id);
                    @endphp
                    @if ($genero)
                        <p>• Género: <span class="font-medium">{{ $genero->nombre }}</span></p>
                    @endif
                @endif
            </div>
        </div>
    @endif --}}

    {{-- Datos ocultos para validación --}}
    <input type="hidden" wire:model="fecha">
    <input type="hidden" wire:model="tamano_id">
    <input type="hidden" wire:model="genero_id">
</div>

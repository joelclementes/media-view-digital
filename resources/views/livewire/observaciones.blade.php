<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
            </path>
        </svg>
        Observaciones
    </h3>

    <div>
        <x-label for="observaciones" value="Observaciones del capturista" />
        <textarea id="observaciones" wire:model.live="observaciones"
            placeholder="Escribe aquí cualquier observación relevante sobre la captura..." rows="4" maxlength="255"
            {{-- Atributo HTML para límite visual --}}
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
        @error('observaciones')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        {{-- Contador de caracteres con límite 255 --}}
        <div class="mt-1 flex justify-between items-center">
            <p class="text-xs text-gray-500">
                Campo opcional - Máximo 255 caracteres
            </p>
            <p
                class="text-xs {{ strlen($observaciones) >= 255
                    ? 'text-red-600 font-bold'
                    : (strlen($observaciones) > 230
                        ? 'text-orange-500'
                        : 'text-gray-500') }}">
                {{ strlen($observaciones) }}/255
                @if (strlen($observaciones) >= 255)
                    - Límite alcanzado
                @endif
            </p>
        </div>

        {{-- Barra de progreso visual --}}
        @php
            $porcentaje = min(100, (strlen($observaciones) / 255) * 100);
        @endphp
        <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
            <div class="h-1.5 rounded-full {{ $porcentaje >= 100 ? 'bg-red-600' : 'bg-blue-600' }}"
                style="width: {{ $porcentaje }}%"></div>
        </div>
    </div>

    {{-- Mostrar resumen si hay observaciones --}}
    {{-- @if ($observaciones)
        <div class="mt-4 p-3 bg-blue-50 rounded-lg">
            <p class="text-sm font-medium text-gray-700 mb-1">Resumen:</p>
            <p class="text-sm text-gray-600 break-words">{{ $observaciones }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ strlen($observaciones) }} caracteres</p>
        </div>
    @endif --}}

    {{-- Botón para limpiar (opcional) --}}
    @if ($observaciones)
        <div class="mt-2 flex justify-end">
            <button type="button" wire:click="limpiar" class="text-xs text-red-600 hover:text-red-800">
                Limpiar campo
            </button>
        </div>
    @endif

    {{-- Dato oculto para validación --}}
    <input type="hidden" wire:model="observaciones">
</div>

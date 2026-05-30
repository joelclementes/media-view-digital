<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14">
            </path>
        </svg>
        Referencias
    </h3>

    <div class="space-y-4">
        {{-- Referencia principal --}}
        <div>
            <x-label for="referencia" value="Referencia" />
            <x-input id="referencia" type="text" wire:model.live="referencia"
                placeholder="Número de expediente, folio, código..." class="w-full" />
            @error('referencia')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Campo opcional</p>
        </div>

        {{-- Referencia domiciliaria (condicional) --}}
        @if ($mostrarDomiciliaria)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div>
                    <x-label for="referencia_domiciliaria" value="Referencia domiciliaria" />
                    <textarea id="referencia_domiciliaria" wire:model.live="referencia_domiciliaria"
                        placeholder="Domicilio, ubicación, coordenadas..." rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    @error('referencia_domiciliaria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Campo opcional</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Resumen de selección --}}
    {{-- @if ($referencia || ($mostrarDomiciliaria && $referencia_domiciliaria))
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <p class="text-sm font-medium text-gray-700 mb-2">Referencias ingresadas:</p>
            <div class="text-sm text-gray-600 space-y-1">
                @if ($referencia)
                    <p>• Referencia: <span class="font-medium">{{ $referencia }}</span></p>
                @endif

                @if ($mostrarDomiciliaria && $referencia_domiciliaria)
                    <p>• Domiciliaria: <span class="font-medium">{{ $referencia_domiciliaria }}</span></p>
                @endif
            </div>
        </div>
    @endif --}}

    {{-- Datos ocultos para validación --}}
    <input type="hidden" wire:model="referencia">
    @if ($mostrarDomiciliaria)
        <input type="hidden" wire:model="referencia_domiciliaria">
    @endif
</div>

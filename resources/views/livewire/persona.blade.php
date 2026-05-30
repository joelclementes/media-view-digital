<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        Datos de la Persona / Sujeto
    </h3>

    @if (!$selectedSujeto)
        {{-- Búsqueda de sujeto --}}
        <div class="mb-4">
            <x-label for="search" value="Buscar sujeto" />
            <div class="relative">
                <x-input id="search" type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Escribe al menos 2 caracteres para buscar..." class="w-full" />

                {{-- Resultados de búsqueda --}}
                @if (!empty($resultados))
                    <div
                        class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        @foreach ($resultados as $sujeto)
                            <div wire:click="selectSujeto({{ $sujeto['id'] }})"
                                class="p-3 hover:bg-blue-50 cursor-pointer border-b last:border-b-0 transition-colors">
                                <p class="font-medium">{{ $sujeto['nombre'] }}</p>
                                {{-- Ocultamos género y municipio de la interfaz --}}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            <p class="mt-1 text-xs text-gray-500">Busca por nombre completo</p>
        </div>
    @else
        {{-- Sujeto seleccionado --}}
        <div class="mb-4 p-3 bg-blue-50 rounded-lg">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-600">Sujeto seleccionado:</p>
                    <p class="font-medium text-blue-800">{{ $selectedSujeto->nombre }}</p>
                    {{-- No mostramos género ni municipio --}}
                </div>
                <button wire:click="limpiarSujeto" type="button"
                    class="text-sm text-red-600 hover:text-red-800 font-medium">
                    Cambiar
                </button>
            </div>
        </div>
    @endif

    {{-- Formulario adicional (se muestra solo cuando hay sujeto seleccionado) --}}
    @if ($mostrarFormulario)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            {{-- Organización Política --}}
            <div>
                <x-label for="organizacion_politica_id" value="Organización política" />
                <select id="organizacion_politica_id" wire:model.live="organizacion_politica_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="" disabled>Seleccionar organización</option>
                    @foreach ($partidos as $partido)
                        <option value="{{ $partido->id }}">
                            {{-- {{ $partido->nombre }} ({{ $partido->siglas }}) - {{ $partido->tipo ?? 'Partido' }} --}}
                            {{ $partido->nombre }} ({{ $partido->tipo ?? 'Partido' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Periodo --}}
            <div>
                <x-label for="periodo_id" value="Periodo" />
                <select id="periodo_id" wire:model.live="periodo_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Seleccionar periodo</option>
                    @foreach ($periodos as $periodo)
                        <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Etapa del sujeto --}}
            <div>
                <x-label for="etapa_sujeto" value="Etapa del sujeto" />
                <select id="etapa_sujeto" wire:model.live="etapa_sujeto"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Seleccionar etapa</option>
                    @foreach ($etapasSujeto as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tipo de elección --}}
            <div>
                <x-label for="tipo_eleccion_id" value="Tipo de elección" />
                <select id="tipo_eleccion_id" wire:model.live="tipo_eleccion_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Seleccionar tipo de elección</option>
                    @foreach ($tiposEleccion as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Mostrar resumen de datos seleccionados --}}
        {{-- @if ($organizacion_politica_id || $periodo_id || $etapa_sujeto || $tipo_eleccion_id)
            <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                <p class="text-sm font-medium text-gray-700 mb-2">Resumen de selección:</p>
                <div class="text-sm text-gray-600 space-y-1">
                    @if ($organizacion_politica_id)
                        @php $partido = $partidos->find($organizacion_politica_id); @endphp
                        <p>• Organización: <span class="font-medium">{{ $partido->nombre ?? '' }}
                                ({{ $partido->siglas ?? '' }})</span></p>
                    @endif
                    @if ($periodo_id)
                        @php $periodo = $periodos->find($periodo_id); @endphp
                        <p>• Periodo: <span class="font-medium">{{ $periodo->nombre ?? '' }}</span></p>
                    @endif
                    @if ($etapa_sujeto)
                        <p>• Etapa: <span class="font-medium">{{ $etapasSujeto[$etapa_sujeto] ?? '' }}</span></p>
                    @endif
                    @if ($tipo_eleccion_id)
                        @php $tipo = $tiposEleccion->find($tipo_eleccion_id); @endphp
                        <p>• Tipo de elección: <span class="font-medium">{{ $tipo->nombre ?? '' }}</span></p>
                    @endif
                </div>
            </div>
        @endif --}}
    @endif

    {{-- Campo oculto para validación del lado del padre --}}
    <input type="hidden" wire:model="sujeto_id">

    {{-- Mostrar error de validación si no hay sujeto --}}
    @error('sujeto_id')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

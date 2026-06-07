<div class="mb-6 p-5 border rounded-lg bg-white shadow-sm">
    <h3 class="text-lg font-semibold mb-4 text-gray-800">
        Datos de la persona / sujeto
    </h3>

    @if (!$sujeto_seleccionado)
        <div class="mb-4">
            <x-label for="busqueda_sujeto" value="Buscar sujeto" />

            <div class="relative">
                <x-input
                    id="busqueda_sujeto"
                    type="text"
                    wire:model.live.debounce.300ms="busqueda_sujeto"
                    placeholder="Escribe al menos 2 caracteres..."
                    class="w-full"
                />

                @if (!empty($resultados_sujetos))
                    <div class="absolute z-20 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
                        @foreach ($resultados_sujetos as $sujeto)
                            <button
                                type="button"
                                wire:click="seleccionarSujeto({{ $sujeto['id'] }})"
                                class="block w-full text-left p-3 hover:bg-primary-50 border-b last:border-b-0"
                            >
                                <span class="font-medium">{{ $sujeto['nombre'] }}</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            @error('sujeto_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    @else
        <div class="mb-4 p-3 bg-primary-50 rounded-lg">
            <div class="flex justify-between items-start gap-4">
                <div>
                    <p class="text-sm text-gray-600">Sujeto seleccionado:</p>
                    <p class="font-medium text-primary-800">{{ $sujeto_seleccionado->nombre }}</p>
                </div>

                <button
                    wire:click="limpiarSujeto"
                    type="button"
                    class="text-sm text-red-600 hover:text-red-800 font-medium"
                >
                    Cambiar
                </button>
            </div>
        </div>
    @endif

    @if ($sujeto_id)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
            <div>
                <x-label for="organizacion_politica_id" value="Organización política" />
                <select
                    id="organizacion_politica_id"
                    wire:model.live="organizacion_politica_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                    <option value="">Seleccionar organización</option>
                    @foreach ($partidos as $partido)
                        <option value="{{ $partido->id }}">
                            {{ $partido->nombre }} ({{ $partido->tipo ?? 'Partido' }})
                        </option>
                    @endforeach
                </select>

                @error('organizacion_politica_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="periodo_id" value="Periodo" />
                <select
                    id="periodo_id"
                    wire:model.live="periodo_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                    <option value="">Seleccionar periodo</option>
                    @foreach ($periodos as $periodo)
                        <option value="{{ $periodo->id }}">{{ $periodo->nombre }}</option>
                    @endforeach
                </select>

                @error('periodo_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="etapa_sujeto" value="Etapa del sujeto" />
                <select
                    id="etapa_sujeto"
                    wire:model.live="etapa_sujeto"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                    <option value="">Seleccionar etapa</option>
                    @foreach ($etapas_sujeto as $valor => $texto)
                        <option value="{{ $valor }}">{{ $texto }}</option>
                    @endforeach
                </select>

                @error('etapa_sujeto')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="tipo_eleccion_id" value="Tipo de elección" />
                <select
                    id="tipo_eleccion_id"
                    wire:model.live="tipo_eleccion_id"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
                    <option value="">Seleccionar tipo de elección</option>
                    @foreach ($tipos_eleccion as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                    @endforeach
                </select>

                @error('tipo_eleccion_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>
    @endif
</div>

<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (!$mostrar_panel_cualitativo)
        <div class="bg-white overflow-hidden shadow-md sm:rounded-md p-4">
            @can('crear_medio')
                <div class="mb-4 flex justify-start">
                    <button type="button" onclick="recuperarInfoAnteriorMedioRadio()"
                        class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar info anterior
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.shared.persona')
                    @include('livewire.medios.radio.partials.medio')
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.radio.partials.publicacion')
                    @include('livewire.medios.shared.autor')
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.shared.observaciones')
                    @include('livewire.medios.radio.partials.archivos')
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="limpiarFormulario"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Limpiar
                    </button>

                    <x-button type="button" wire:click="guardar" wire:loading.attr="disabled">
                        {{ $registro_editando_id ? 'Actualizar registro' : 'Guardar registro' }}
                    </x-button>
                </div>
            @endcan
        </div>

        <div class="mt-8 bg-white overflow-hidden shadow-md sm:rounded-md">
            <div class="border-b border-gray-200 p-4">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Registros capturados</h3>
                        <p class="text-sm text-gray-500">Consulta, filtra y revisa los registros de radio.</p>
                        <p class="mt-2 text-sm text-gray-600">
                            Se encontraron <span class="font-semibold text-gray-800">{{ $registros->total() }}</span>
                            registros.
                        </p>
                    </div>

                    <div class="w-full lg:max-w-xl">
                        <x-label for="busqueda_tabla" value="Buscar registro" />
                        <x-input id="busqueda_tabla" type="text" wire:model.live.debounce.500ms="busqueda_tabla"
                            placeholder="Buscar por Id, sujeto, organización, medio, municipio o tipo..."
                            class="w-full" />
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <button type="button" wire:click="alternarFiltrosTabla"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        <span>{{ $mostrar_filtros_tabla ? 'Ocultar filtros' : 'Mostrar filtros' }}</span>
                        <svg class="ml-2 h-4 w-4 transition-transform {{ $mostrar_filtros_tabla ? 'rotate-180' : '' }}"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div class="text-sm text-gray-500">
                        <span wire:loading.remove
                            wire:target="fecha_inicio_registro,fecha_fin_registro,filtro_tipo_eleccion_id,busqueda_tabla,cantidad_por_pagina">
                            Mostrando {{ $registros->firstItem() ?? 0 }} - {{ $registros->lastItem() ?? 0 }} de
                            {{ $registros->total() }} registros.
                        </span>
                        <span wire:loading
                            wire:target="fecha_inicio_registro,fecha_fin_registro,filtro_tipo_eleccion_id,busqueda_tabla,cantidad_por_pagina"
                            class="text-blue-600">
                            Actualizando resultados...
                        </span>
                    </div>
                </div>

                @if ($mostrar_filtros_tabla)
                    <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div>
                                <x-label for="fecha_inicio_registro" value="Fecha inicial" />
                                <x-input id="fecha_inicio_registro" type="date"
                                    wire:model.live="fecha_inicio_registro" class="w-full" />
                            </div>

                            <div>
                                <x-label for="fecha_fin_registro" value="Fecha final" />
                                <x-input id="fecha_fin_registro" type="date" wire:model.live="fecha_fin_registro"
                                    class="w-full" />
                            </div>

                            <div>
                                <x-label for="filtro_tipo_eleccion_id" value="Candidatura" />
                                <select id="filtro_tipo_eleccion_id" wire:model.live="filtro_tipo_eleccion_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Todas</option>
                                    @foreach ($tipos_eleccion as $tipo)
                                        <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-label for="cantidad_por_pagina" value="Mostrar" />
                                <select id="cantidad_por_pagina" wire:model.live="cantidad_por_pagina"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="10">10 registros</option>
                                    <option value="25">25 registros</option>
                                    <option value="50">50 registros</option>
                                    <option value="100">100 registros</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button type="button" wire:click="limpiarFiltrosTabla"
                                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                Limpiar filtros
                            </button>
                        </div>
                    </div>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Id</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Organización</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Sujeto</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Medio</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Tipo</th>
                            @if (auth()->user()->hasAnyRole(['Administrador', 'Super usuario', 'Super Usuario', 'Capturista']))
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">
                                    Capturó
                                </th>
                            @endif
                            <th class="px-4 py-3 text-center font-semibold text-gray-700">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse ($registros as $registro)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-800">#{{ $registro->id }}</td>
                                <td class="px-4 py-3">{{ $registro->organizacion_nombre ?? 'Sin organización' }}</td>
                                <td class="px-4 py-3">{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}</td>
                                <td class="px-4 py-3">
                                    {{ $registro->publicacion_fecha ? \Carbon\Carbon::parse($registro->publicacion_fecha)->format('d/m/Y') : 'Sin fecha' }}
                                    @if ($registro->publicacion_hora)
                                        <span
                                            class="block text-xs text-gray-500">{{ \Carbon\Carbon::parse($registro->publicacion_hora)->format('H:i') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $registro->medio_nombre ?: 'Sin medio' }}
                                    @if ($registro->medio_siglas)
                                        <span class="block text-xs text-gray-500">{{ $registro->medio_siglas }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $registro->publicacion_tipo ?: 'Sin tipo' }}</td>
                                @if (auth()->user()->hasAnyRole(['Administrador', 'Super usuario', 'Super Usuario', 'Capturista']))
                                    <td class="px-4 py-3">
                                        {{ $registro->capturista?->name ?? 'Sin capturista' }}
                                    </td>
                                @endif
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        @can('crear_medio')
                                            <button type="button" title="Cualitativos"
                                                wire:click="abrirCualitativos({{ $registro->id }})"
                                                class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-file-diff class="h-5 w-5" />
                                            </button>
                                        @endcan

                                        <a href="{{ route('m-radio-testigo', $registro->id) }}" target="_blank"
                                            title="Testigo"
                                            class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                            <x-lucide-file-type class="h-5 w-5" />
                                        </a>

                                        @can('editar_medio')
                                            <button type="button" wire:click="editar({{ $registro->id }})"
                                                title="Editar"
                                                class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-pencil class="h-5 w-5" />
                                            </button>
                                        @else
                                            <a href="{{ route('m-radio-show', $registro->id) }}" title="Ver"
                                                class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-eye class="h-5 w-5" />
                                            </a>
                                        @endcan

                                        @can('eliminar_medio')
                                            <button type="button" title="Eliminar"
                                                wire:click="confirmarEliminacion({{ $registro->id }})"
                                                class="rounded-md p-1.5 text-red-600 hover:bg-red-50 hover:text-red-800">
                                                <x-lucide-trash-2 class="h-5 w-5" />
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">No hay registros con
                                    los filtros seleccionados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $registros->links() }}
            </div>
        </div>

        @if ($mostrar_modal_eliminar)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900">Confirmar eliminación</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        ¿Seguro que deseas eliminar <span
                            class="font-semibold">{{ $registro_eliminar_referencia }}</span>?
                        Esta acción también eliminará los audios relacionados.
                    </p>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="cancelarEliminacion"
                            class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="button" wire:click="eliminar"
                            class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Datos cualitativos</h3>
                    <p class="text-sm text-gray-500">Registro de radio #{{ $registro_cualitativo_id }}</p>
                </div>

                <button type="button" wire:click="cerrarCualitativos"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Volver a registros
                </button>
            </div>

            {{-- <div class="grid grid-cols-1 gap-6 lg:grid-cols-2"> --}}
            <div class="rounded-lg bg-white p-5 shadow-md">
                <h4 class="mb-4 text-lg font-semibold text-gray-800">
                    Datos del registro
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Fecha de
                            publicación:</span>{{ !empty($registro_cualitativo['publicacion_fecha']) ? \Carbon\Carbon::parse($registro_cualitativo['publicacion_fecha'])->format('d/m/Y') : 'Sin fecha' }}
                    </div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Medio
                            radio:</span>{{ $registro_cualitativo['medio_nombre'] ?? 'Sin medio' }}</div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Locutor:</span>{{ $registro_cualitativo['nombre_autor'] ?? 'Sin autor' }}
                    </div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Tipo de
                            publicación:</span>{{ $registro_cualitativo['publicacion_tipo'] ?? 'Sin tipo' }}</div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Ubicación:</span>{{ $registro_cualitativo['publicacion_ubicacion'] ?? 'Sin ubicación' }}
                    </div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Cobertura</span>{{ $registro_cualitativo['medio_cobertura'] ?? 'Sin cobertura' }}
                    </div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Actor</span>{{ $registro_cualitativo['sujeto_nombre'] ?? 'Sin sujeto' }}
                    </div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Género:</span>{{ $registro_cualitativo['sujeto_genero'] ?? 'Sin sujeto' }}
                    </div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Org.
                            Política:</span>{{ $registro_cualitativo['organizacion_nombre'] ?? 'Sin organización' }}
                    </div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Etapa del
                            sujeto:</span>{{ $registro_cualitativo['etapa_sujeto'] ?? 'Sin especificar' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Tiempo:</span>
                        @if (!empty($registro_cualitativo['publicacion_tiempo']))
                            {{ gmdate('H:i:s', (int) $registro_cualitativo['publicacion_tiempo']) }}
                        @else
                            Sin tiempo
                        @endif
                    </div>
                </div>
                {{-- <dl class="space-y-1 text-sm">
                        <div>
                            <dt class="font-semibold text-gray-600">Fecha de publicación:</dt>
                            <dd class="text-primary-800">
                                {{ !empty($registro_cualitativo['publicacion_fecha']) ? \Carbon\Carbon::parse($registro_cualitativo['publicacion_fecha'])->format('d/m/Y') : 'Sin fecha' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Medio radio:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['medio_nombre'] ?? 'Sin medio' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Locutor:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['nombre_autor'] ?? 'Sin autor' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Tipo de publicación:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['publicacion_tipo'] ?? 'Sin tipo' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Ubicación:</dt>
                            <dd class="text-primary-800">
                                {{ $registro_cualitativo['publicacion_ubicacion'] ?? 'Sin ubicación' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Cobertura:</dt>
                            <dd class="text-primary-800">
                                {{ $registro_cualitativo['medio_cobertura'] ?? 'Sin cobertura' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Actor:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['sujeto_nombre'] ?? 'Sin sujeto' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Género:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['sujeto_genero'] ?? 'Sin sujeto' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Org. política:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['organizacion_nombre'] ?? 'Sin organización' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Etapa del sujeto:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['etapa_sujeto'] ?? 'Sin especificar' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Tiempo:</dt>
                            <dd class="text-primary-800">
                                @if (!empty($registro_cualitativo['publicacion_tiempo']))
                                    {{ gmdate('H:i:s', (int) $registro_cualitativo['publicacion_tiempo']) }}
                                @else
                                    Sin tiempo
                                @endif
                            </dd>
                        </div>
                    </dl> --}}
            </div>

            <div class="rounded-lg bg-white p-5 shadow-md">
                <h4 class="mb-4 text-lg font-semibold text-gray-800">Audio</h4>

                @if (!empty($audios_cualitativos))
                    <div class="space-y-4">
                        @foreach ($audios_cualitativos as $audio)
                            <div class="rounded-lg border border-gray-200 bg-white p-3">
                                <p class="mb-2 truncate text-sm font-medium text-gray-700">
                                    {{ basename($audio) }}
                                </p>
                                <audio controls preload="metadata" class="w-full">
                                    <source src="{{ Storage::url($audio) }}" type="audio/mpeg">
                                    Tu navegador no soporta el reproductor de audio.
                                </audio>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="flex h-[360px] items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-sm text-gray-500">
                        Este registro no tiene audios cargados.
                    </div>
                @endif
            </div>
            {{-- </div> --}}

            <div class="rounded-lg bg-white p-5 shadow-md">
                <div class="mb-5 flex justify-start">
                    <button type="button" onclick="recuperarDatosCualitativosMedioRadio()"
                        class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar datos cualitativos
                    </button>
                </div>

                <h4 class="mb-5 text-lg font-semibold text-gray-800">Formulario cualitativo</h4>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <x-label for="cuali_valoracion" value="Valoración" />
                        <select id="cuali_valoracion" wire:model="cuali_valoracion"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($valoraciones as $valoracion)
                                <option value="{{ $valoracion }}">{{ $valoracion }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label value="Lenguaje inclusivo" />
                        <div class="mt-2 flex gap-6">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="radio" wire:model="cuali_lenguaje_inclusivo" value="Si"
                                    class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>Sí</span>
                            </label>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="radio" wire:model="cuali_lenguaje_inclusivo" value="No"
                                    class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span>No</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <x-label for="cuali_estereotipo" value="Estereotipo" />
                        <select id="cuali_estereotipo" wire:model="cuali_estereotipo"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($estereotipos as $estereotipo)
                                <option value="{{ $estereotipo }}">{{ $estereotipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="cuali_violencia_temas_id" value="Tema de violencia" />
                        <select id="cuali_violencia_temas_id" wire:model="cuali_violencia_temas_id"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($violencia_temas as $tema)
                                <option value="{{ $tema->id }}">{{ $tema->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="cuali_tipos_eleccion_id" value="Candidatura" />
                        <select id="cuali_tipos_eleccion_id" wire:model="cuali_tipos_eleccion_id"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($tipos_eleccion as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="cuali_criterio_evaluacion" value="Criterio de evaluación" />
                        <select id="cuali_criterio_evaluacion" wire:model="cuali_criterio_evaluacion"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($criterios_evaluacion as $criterio)
                                <option value="{{ $criterio }}">{{ $criterio }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <x-label for="cuali_resumen" value="Resumen" />
                        <textarea id="cuali_resumen" wire:model.live.debounce.500ms="cuali_resumen" rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        <x-input-error for="cuali_resumen" class="mt-1" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cerrarCualitativos"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>
                    @can('crear_datos_cualitativos')
                        <x-button type="button" wire:click="guardarCualitativos" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="guardarCualitativos">Guardar cualitativos</span>
                            <span wire:loading wire:target="guardarCualitativos">Guardando...</span>
                        </x-button>
                    @endcan
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        const RADIO_INFO_KEY = 'media_view_digital_radio_info_anterior';
        const RADIO_CUALITATIVOS_KEY = 'media_view_digital_radio_datos_cualitativos';

        $wire.on('radio-registro-guardado', (event) => {
            const datos = event.datos ?? event[0]?.datos ?? null;
            if (!datos) return;
            localStorage.setItem(RADIO_INFO_KEY, JSON.stringify(datos));
        });

        $wire.on('radio-cualitativos-guardados', (event) => {
            const datos = event.datos ?? event[0]?.datos ?? null;
            if (!datos) return;
            localStorage.setItem(RADIO_CUALITATIVOS_KEY, JSON.stringify(datos));
        });

        window.recuperarInfoAnteriorMedioRadio = function() {
            const datosGuardados = localStorage.getItem(RADIO_INFO_KEY);
            if (!datosGuardados) {
                alert('No hay información anterior guardada.');
                return;
            }
            try {
                $wire.recuperarInfoAnterior(JSON.parse(datosGuardados));
            } catch (error) {
                alert('No se pudo recuperar la información anterior.');
            }
        };

        window.recuperarDatosCualitativosMedioRadio = function() {
            const datosGuardados = localStorage.getItem(RADIO_CUALITATIVOS_KEY);
            if (!datosGuardados) {
                alert('No hay datos cualitativos anteriores guardados.');
                return;
            }
            try {
                $wire.recuperarDatosCualitativos(JSON.parse(datosGuardados));
            } catch (error) {
                alert('No se pudieron recuperar los datos cualitativos.');
            }
        };
    </script>
@endscript

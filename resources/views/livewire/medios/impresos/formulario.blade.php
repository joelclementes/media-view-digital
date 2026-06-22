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
                    <button type="button" onclick="recuperarInfoAnteriorMedioImpreso()"
                        class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar info anterior
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.shared.persona')
                    @include('livewire.medios.impresos.partials.medio')
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.impresos.partials.publicacion')
                    @include('livewire.medios.shared.autor')
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.shared.referencia')
                    @include('livewire.medios.shared.observaciones')
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.shared.archivos')
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
            </div>
        @endcan
        <div class="mt-8 bg-white overflow-hidden shadow-md sm:rounded-md">
            <div class="border-b border-gray-200 p-4">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">
                            Registros capturados
                        </h3>

                        <p class="text-sm text-gray-500">
                            Consulta, filtra y revisa los registros de medios impresos.
                        </p>

                        <p class="mt-2 text-sm text-gray-600">
                            Se encontraron
                            <span class="font-semibold text-gray-800">
                                {{ $registros->total() }}
                            </span>
                            registros.
                        </p>
                    </div>

                    <div class="w-full lg:max-w-xl">
                        <x-label for="busqueda_tabla" value="Buscar registro" />

                        <x-input id="busqueda_tabla" type="text" wire:model.live.debounce.500ms="busqueda_tabla"
                            placeholder="Buscar por Id, sujeto, organización, medio, referencia, sección o página..."
                            class="w-full" />
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <button type="button" wire:click="alternarFiltrosTabla"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        <span>
                            {{ $mostrar_filtros_tabla ? 'Ocultar filtros' : 'Mostrar filtros' }}
                        </span>

                        <svg class="ml-2 h-4 w-4 transition-transform {{ $mostrar_filtros_tabla ? 'rotate-180' : '' }}"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div class="text-sm text-gray-500">
                        <span wire:loading.remove
                            wire:target="fecha_inicio_registro,fecha_fin_registro,filtro_tipo_eleccion_id,busqueda_tabla,cantidad_por_pagina">
                            Mostrando {{ $registros->firstItem() ?? 0 }} - {{ $registros->lastItem() ?? 0 }}
                            de {{ $registros->total() }} registros.
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
                                        <option value="{{ $tipo->id }}">
                                            {{ $tipo->nombre }}
                                        </option>
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
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Referencia</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Sujeto</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Medio impreso</th>
                            @if (!auth()->user()->hasRole('Consultor'))
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
                                <td class="px-4 py-3">{{ $registro->referencia ?: 'Sin referencia' }}</td>
                                <td class="px-4 py-3">{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    {{ $registro->publicacion_fecha ? \Carbon\Carbon::parse($registro->publicacion_fecha)->format('d/m/Y') : 'Sin fecha' }}
                                </td>
                                <td class="px-4 py-3">{{ $registro->medio_prensa_nombre ?? 'Sin medio' }}</td>
                                @if (!auth()->user()->hasRole('Consultor'))
                                    <td class="px-4 py-3">{{ $registro->capturista?->name ?? 'Sin capturista' }}</td>
                                @endif
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2 text-primary-600">
                                        @can('crear_medio')
                                            <button type="button" title="Cualitativos"
                                                wire:click="abrirCualitativos({{ $registro->id }})"
                                                class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-file-diff class="h-5 w-5" />
                                            </button>
                                        @endcan

                                        <a href="{{ route('m-impresos-testigo', $registro->id) }}" target="_blank"
                                            title="Testigo"
                                            class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                            <x-lucide-file-type class="h-5 w-5" />
                                        </a>

                                        @can('editar_medio')
                                            <button type="button" title="Editar"
                                                wire:click="editar({{ $registro->id }})"
                                                class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-pencil class="h-5 w-5" />
                                            </button>
                                        @else
                                            <a href="{{ route('m-impresos-show', $registro->id) }}" title="Ver"
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
                                <td colspan="7" class="px-4 py-10 text-center text-gray-500">
                                    No hay registros capturados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-t border-gray-200 p-4">
                {{ $registros->links() }}
            </div>
        </div>
    @endif

    @if ($mostrar_modal_eliminar)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
            <div class="w-full max-w-md rounded-xl bg-white shadow-2xl">
                <div class="border-b px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                            <x-lucide-trash-2 class="h-6 w-6 text-red-600" />
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                Eliminar registro
                            </h3>

                            <p class="text-sm text-gray-500">
                                Esta acción no se puede deshacer.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5">
                    <p class="text-sm text-gray-700">
                        Se eliminará el registro:
                    </p>

                    <div class="mt-3 rounded-lg bg-gray-50 p-3 text-sm font-medium text-gray-800">
                        {{ $registro_eliminar_referencia }}
                    </div>

                    <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        También se eliminarán permanentemente todas las imágenes asociadas en el storage.
                    </div>
                </div>

                <div class="flex justify-end gap-3 border-t px-6 py-4">
                    <button type="button" wire:click="cancelarEliminacion"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>

                    <button type="button" wire:click="eliminar" wire:loading.attr="disabled" wire:target="eliminar"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                        <span wire:loading.remove wire:target="eliminar">
                            Eliminar definitivamente
                        </span>

                        <span wire:loading wire:target="eliminar">
                            Eliminando...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($mostrar_panel_cualitativo)
        <div class="space-y-6">
            <div class="flex items-center justify-between">

                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Captura de datos cualitativos</h3>
                    <p class="text-sm text-gray-500">Registro #{{ $registro_cualitativo_id }}</p>
                </div>

                <button type="button" wire:click="cerrarCualitativos"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Volver
                </button>
            </div>

            {{-- <div class="grid grid-cols-1 gap-6 lg:grid-cols-2"> --}}
            <div class="rounded-lg bg-white p-5 shadow-md">
                <h4 class="mb-4 text-lg font-semibold text-gray-800">
                    Datos del registro
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Org.
                            Política:</span>{{ $registro_cualitativo['organizacion'] ?? '' }}</div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Sujeto</span>{{ $registro_cualitativo['sujeto'] ?? '' }}
                    </div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Género del
                            sujeto:</span>{{ $registro_cualitativo['genero_sujeto'] ?? '' }}</div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Periodo:</span>{{ $registro_cualitativo['periodo'] ?? '' }}
                    </div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Fecha de
                            publicación:</span>{{ $registro_cualitativo['fecha'] ?? '' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Medio
                            impreso:</span>{{ $registro_cualitativo['medio'] ?? '' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Tamaño
                            pub.:</span>{{ $registro_cualitativo['tamano'] ?? '' }}</div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Género:</span>{{ $registro_cualitativo['genero'] ?? '' }}
                    </div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Sección:</span>{{ $registro_cualitativo['seccion'] ?? '' }}
                    </div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Página:</span>{{ $registro_cualitativo['pagina'] ?? '' }}
                    </div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Referencia:</span>{{ $registro_cualitativo['referencia'] ?? '' }}
                    </div>
                    <div><span
                            class="block text-xs font-semibold uppercase text-gray-500">Observ.:</span>{{ $registro_cualitativo['observaciones'] ?? '' }}
                    </div>
                </div>


            </div>

            <div class="rounded-lg bg-white p-5 shadow-md">
                <h4 class="mb-4 text-lg font-semibold text-gray-800">
                    Imágenes del registro
                </h4>

                {{-- @if (count($imagenes_cualitativas) > 0)
                        <button type="button" wire:click="abrirModalImagen(0)"
                            class="block w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                            <img src="{{ asset('storage/' . $imagenes_cualitativas[0]) }}" alt="Imagen del registro"
                                class="h-[360px] w-full object-contain">
                        </button>

                        <p class="mt-3 text-center text-sm text-gray-500">
                            Clic en la imagen para ampliar.
                            {{ count($imagenes_cualitativas) }} imagen(es) disponible(s).
                        </p>
                    @else
                        <div
                            class="flex h-[360px] items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-sm text-gray-500">
                            Este registro no tiene imágenes.
                        </div>
                    @endif --}}
                @if (count($imagenes_cualitativas) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($imagenes_cualitativas as $imagen)
                            <a href="{{ asset('storage/' . $imagen) }}" target="_blank"
                                class="block overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen del soporte"
                                    class="h-48 w-full object-cover">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="rounded-md border border-gray-200 bg-gray-50 p-4 text-sm text-gray-500">Este registro no
                        tiene imágenes cargadas.</p>
                @endif
            </div>
            {{-- </div> --}}

            <div class="rounded-lg bg-white p-5 shadow-md">

                <div class="flex justify-start mb-5">
                    <button type="button" onclick="recuperarDatosCualitativosMedioImpreso()"
                        class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar datos cualitativos
                    </button>
                </div>
                <h4 class="mb-5 text-lg font-semibold text-gray-800">Formulario cualitativo</h4>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <x-label for="cuali_valoracion" value="Valoración" />
                        <select id="cuali_valoracion" wire:model="cuali_valoracion"
                            class="w-full rounded-md border-gray-300">
                            <option value="">Selecciona...</option>
                            <option value="Positiva">Positiva</option>
                            <option value="Negativa">Negativa</option>
                            <option value="Neutral">Neutral</option>
                        </select>
                    </div>

                    <div>
                        <x-label value="Lenguaje inclusivo" />
                        <div class="mt-2 flex gap-6">
                            <label><input type="radio" wire:model="cuali_lenguaje_inclusivo" value="Si">
                                Sí</label>
                            <label><input type="radio" wire:model="cuali_lenguaje_inclusivo" value="No">
                                No</label>
                        </div>
                    </div>

                    <div>
                        <x-label for="cuali_estereotipo" value="Estereotipo" />
                        <select id="cuali_estereotipo" wire:model="cuali_estereotipo"
                            class="w-full rounded-md border-gray-300">
                            <option value="">Selecciona...</option>
                            <option value="NA">NA</option>
                            <option value="Personas indígenas">Personas indígenas</option>
                            <option value="Creencias religiosas de las personas">Creencias religiosas de las personas
                            </option>
                            <option value="Personas afroamericanas">Personas afroamericanas</option>
                            <option value="Personas de la diversidad sexual o de género">Personas de la diversidad
                                sexual o de género</option>
                            <option value="Personas jóvenes">Personas jóvenes</option>
                            <option value="Personas mayores">Personas mayores</option>
                            <option value="Personas con discapacidad">Personas con discapacidad</option>
                            <option value="Personas que viven con VIH">Personas que viven con VIH</option>
                            <option value="Víctimas del delito">Víctimas del delito</option>
                        </select>
                    </div>

                    <div>
                        <x-label for="cuali_criterio_evaluacion" value="Criterio de evaluación" />
                        <select id="cuali_criterio_evaluacion" wire:model="cuali_criterio_evaluacion"
                            class="w-full rounded-md border-gray-300">
                            <option value="">Selecciona...</option>
                            <option value="Fotografía B&N">Fotografía B&N</option>
                            <option value="Fotografía color">Fotografía color</option>
                            <option value="Caricatura">Caricatura</option>
                            <option value="Emblema">Emblema</option>
                            <option value="Gráficos">Gráficos</option>
                        </select>
                    </div>

                    <div>
                        <x-label for="cuali_modalidad" value="Modalidad" />
                        <select id="cuali_modalidad" wire:model="cuali_modalidad"
                            class="w-full rounded-md border-gray-300">
                            <option value="">Selecciona...</option>
                            <option value="Politica">Política</option>
                            <option value="Electoral">Electoral</option>
                        </select>
                    </div>

                    <div>
                        <x-label for="cuali_periodicidad" value="Periodicidad" />
                        <x-input id="cuali_periodicidad" wire:model="cuali_periodicidad" class="w-full" />
                    </div>

                    <div>
                        <x-label for="cuali_tiraje" value="Tiraje" />
                        <x-input id="cuali_tiraje" type="number" wire:model="cuali_tiraje" class="w-full" />
                    </div>

                    <div>
                        <x-label for="cuali_circulacion" value="Circulación" />
                        <select id="cuali_circulacion" wire:model="cuali_circulacion"
                            class="w-full rounded-md border-gray-300">
                            <option value="">Selecciona...</option>
                            <option value="Nacional">Nacional</option>
                            <option value="Regional">Regional</option>
                            <option value="Local">Local</option>
                        </select>
                    </div>

                    <div>
                        <x-label for="cuali_resumen" value="Resumen" />

                        <textarea id="cuali_resumen" wire:model.live.debounce.500ms="cuali_resumen" maxlength="255" rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>

                        <div class="mt-1 flex justify-between text-xs text-gray-500">
                            <span>Máximo 255 caracteres.</span>
                            <span>{{ strlen($cuali_resumen ?? '') }}/255</span>
                        </div>

                        <x-input-error for="cuali_resumen" class="mt-1" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cerrarCualitativos"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </button>

                    <x-button type="button" wire:click="guardarCualitativos">
                        Guardar cualitativos
                    </x-button>
                </div>
            </div>
        </div>

        @if ($modal_imagen_abierto && isset($imagenes_cualitativas[$imagen_actual_indice]))
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4">
                <button type="button" wire:click="cerrarModalImagen"
                    class="absolute right-4 top-4 rounded-full bg-white/10 px-3 py-2 text-white hover:bg-white/20">
                    ✕
                </button>

                @if (count($imagenes_cualitativas) > 1)
                    <button type="button" wire:click="imagenAnterior"
                        class="absolute left-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 px-4 py-3 text-3xl text-white hover:bg-white/20">
                        ‹
                    </button>
                @endif

                <img src="{{ asset('storage/' . $imagenes_cualitativas[$imagen_actual_indice]) }}"
                    alt="Imagen ampliada" class="max-h-[90vh] max-w-[90vw] object-contain">

                @if (count($imagenes_cualitativas) > 1)
                    <button type="button" wire:click="imagenSiguiente"
                        class="absolute right-4 top-1/2 -translate-y-1/2 rounded-full bg-white/10 px-4 py-3 text-3xl text-white hover:bg-white/20">
                        ›
                    </button>

                    <div class="absolute bottom-4 rounded-full bg-white/10 px-4 py-2 text-sm text-white">
                        {{ $imagen_actual_indice + 1 }} / {{ count($imagenes_cualitativas) }}
                    </div>
                @endif
            </div>
        @endif

    @endif
</div>

@script
    <script>
        const IMPRESOS_INFO_KEY = 'media_view_digital_impresos_info_anterior';
        const IMPRESOS_CUALITATIVOS_KEY = 'media_view_digital_impresos_datos_cualitativos';

        $wire.on('impresos-registro-guardado', (event) => {
            const datos = event.datos ?? event[0]?.datos ?? null;

            if (!datos) {
                return;
            }

            localStorage.setItem(IMPRESOS_INFO_KEY, JSON.stringify(datos));
        });

        $wire.on('impresos-cualitativos-guardados', (event) => {
            const datos = event.datos ?? event[0]?.datos ?? null;

            if (!datos) {
                return;
            }

            localStorage.setItem(IMPRESOS_CUALITATIVOS_KEY, JSON.stringify(datos));
        });

        window.recuperarInfoAnteriorMedioImpreso = function() {
            const datosGuardados = localStorage.getItem(IMPRESOS_INFO_KEY);

            if (!datosGuardados) {
                alert('No hay información anterior guardada.');
                return;
            }

            try {
                const datos = JSON.parse(datosGuardados);
                $wire.recuperarInfoAnterior(datos);
            } catch (error) {
                alert('No se pudo recuperar la información anterior.');
            }
        };

        window.recuperarDatosCualitativosMedioImpreso = function() {
            const datosGuardados = localStorage.getItem(IMPRESOS_CUALITATIVOS_KEY);

            if (!datosGuardados) {
                alert('No hay datos cualitativos anteriores guardados.');
                return;
            }

            try {
                const datos = JSON.parse(datosGuardados);
                $wire.recuperarDatosCualitativos(datos);
            } catch (error) {
                alert('No se pudieron recuperar los datos cualitativos.');
            }
        };
    </script>
@endscript

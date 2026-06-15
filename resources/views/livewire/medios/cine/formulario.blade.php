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
                    <button type="button" onclick="recuperarInfoAnteriorMedioCine()"
                        class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar info anterior
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.shared.persona')
                    @include('livewire.medios.cine.partials.medio')
                    @include('livewire.medios.cine.partials.publicacion')
                    @include('livewire.medios.shared.observaciones')
                    @include('livewire.medios.cine.partials.archivos')
                </div>

                <div class="mb-8 flex justify-end gap-3">
                    <button type="button" wire:click="limpiarFormulario"
                        class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Limpiar
                    </button>
                    <x-button type="button" wire:click="guardar" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="guardar">{{ $registro_editando_id ? 'Actualizar registro' : 'Guardar registro' }}</span>
                        <span wire:loading wire:target="guardar">Guardando...</span>
                    </x-button>
                </div>
            @endcan

            <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800">Registros de cine</h3>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <x-input type="text" wire:model.live.debounce.500ms="busqueda_tabla"
                        placeholder="Buscar sujeto, cine, municipio..." class="w-full sm:w-72" />

                    <button type="button" wire:click="alternarFiltrosTabla"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Filtros
                    </button>
                </div>
            </div>

            @if ($mostrar_filtros_tabla)
                <div class="mb-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                        <div>
                            <x-label for="fecha_inicio_registro" value="Fecha inicial" />
                            <x-input id="fecha_inicio_registro" type="date" wire:model.live="fecha_inicio_registro" class="w-full" />
                        </div>

                        <div>
                            <x-label for="fecha_fin_registro" value="Fecha final" />
                            <x-input id="fecha_fin_registro" type="date" wire:model.live="fecha_fin_registro" class="w-full" />
                        </div>

                        <div>
                            <x-label for="filtro_tipo_eleccion_id" value="Tipo de elección" />
                            <select id="filtro_tipo_eleccion_id" wire:model.live="filtro_tipo_eleccion_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                @foreach ($tipos_eleccion as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-label for="filtro_municipio_id" value="Municipio" />
                            <select id="filtro_municipio_id" wire:model.live="filtro_municipio_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Todos</option>
                                @foreach ($municipios as $municipio)
                                    <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
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

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Id</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Organización</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Sujeto</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Cine</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Ubicación</th>
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
                                        <span class="block text-xs text-gray-500">{{ \Carbon\Carbon::parse($registro->publicacion_hora)->format('H:i') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $registro->cine_nombre_comercial ?: ($registro->cine_nombre ?: 'Sin cine') }}
                                    @if ($registro->medio_sala)
                                        <span class="block text-xs text-gray-500">Sala: {{ $registro->medio_sala }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $registro->municipio_nombre ?? 'Sin municipio' }}
                                    @if ($registro->localidad_nombre)
                                        <span class="block text-xs text-gray-500">{{ $registro->localidad_nombre }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        @can('crear_medio')
                                            <button type="button" title="Cualitativos" wire:click="abrirCualitativos({{ $registro->id }})"
                                                class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-file-diff class="h-5 w-5" />
                                            </button>
                                        @endcan

                                        <a href="{{ route('m-cine-testigo', $registro->id) }}" target="_blank" title="Testigo"
                                            class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                            <x-lucide-file-type class="h-5 w-5" />
                                        </a>

                                        @can('editar_medio')
                                            <button type="button" wire:click="editar({{ $registro->id }})" title="Editar"
                                                class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-pencil class="h-5 w-5" />
                                            </button>
                                        @else
                                            <a href="{{ route('m-cine-show', $registro->id) }}" title="Ver"
                                                class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-eye class="h-5 w-5" />
                                            </a>
                                        @endcan

                                        @can('eliminar_medio')
                                            <button type="button" title="Eliminar" wire:click="confirmarEliminacion({{ $registro->id }})"
                                                class="rounded-md p-1.5 text-red-600 hover:bg-red-50 hover:text-red-800">
                                                <x-lucide-trash-2 class="h-5 w-5" />
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">No hay registros con los filtros seleccionados.</td>
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
                    <p class="mt-2 text-sm text-gray-600">¿Seguro que deseas eliminar <span class="font-semibold">{{ $registro_eliminar_referencia }}</span>? Esta acción también eliminará los archivos relacionados.</p>
                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="cancelarEliminacion" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                        <button type="button" wire:click="eliminar" class="rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Eliminar</button>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Datos cualitativos</h3>
                    <p class="text-sm text-gray-500">Registro de cine #{{ $registro_cualitativo_id }}</p>
                </div>
                <button type="button" wire:click="cerrarCualitativos" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Volver</button>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-lg bg-white p-5 shadow-md">
                    <h4 class="mb-4 text-lg font-semibold text-gray-800">Datos principales</h4>
                    <div class="grid grid-cols-1 gap-3 text-sm md:grid-cols-2">
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Organización</p><p class="font-medium text-gray-800">{{ $registro_cualitativo['organizacion_nombre'] ?? 'Sin organización' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Sujeto</p><p class="font-medium text-gray-800">{{ $registro_cualitativo['sujeto_nombre'] ?? 'Sin sujeto' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Cine</p><p class="font-medium text-gray-800">{{ $registro_cualitativo['cine_nombre_comercial'] ?? ($registro_cualitativo['cine_nombre'] ?? 'Sin cine') }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Fecha</p><p class="font-medium text-gray-800">{{ !empty($registro_cualitativo['publicacion_fecha']) ? \Carbon\Carbon::parse($registro_cualitativo['publicacion_fecha'])->format('d/m/Y') : 'Sin fecha' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Municipio</p><p class="font-medium text-gray-800">{{ $registro_cualitativo['municipio_nombre'] ?? 'Sin municipio' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Localidad</p><p class="font-medium text-gray-800">{{ $registro_cualitativo['localidad_nombre'] ?? 'Sin localidad' }}</p></div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-5 shadow-md">
                    <h4 class="mb-4 text-lg font-semibold text-gray-800">Archivo / video</h4>
                    @if (!empty($videos_cualitativos))
                        <div class="space-y-4">
                            @foreach ($videos_cualitativos as $video)
                                <div class="rounded-lg border border-gray-200 bg-white p-3">
                                    <p class="mb-2 truncate text-sm font-medium text-gray-700">{{ basename($video) }}</p>
                                    <video controls preload="metadata" class="w-full rounded-lg"><source src="{{ Storage::url($video) }}">Tu navegador no soporta el reproductor de video.</video>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex h-[260px] items-center justify-center rounded-lg border border-dashed border-gray-300 bg-gray-50 text-sm text-gray-500">Este registro no tiene archivos cargados.</div>
                    @endif
                </div>
            </div>

            <div class="rounded-lg bg-white p-5 shadow-md">
                <div class="mb-5 flex justify-start">
                    <button type="button" onclick="recuperarDatosCualitativosMedioCine()" class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar datos cualitativos
                    </button>
                </div>

                <h4 class="mb-5 text-lg font-semibold text-gray-800">Formulario cualitativo</h4>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <x-label for="cuali_valoracion" value="Valoración" />
                        <select id="cuali_valoracion" wire:model="cuali_valoracion" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($valoraciones as $valoracion)<option value="{{ $valoracion }}">{{ $valoracion }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <x-label value="Lenguaje inclusivo" />
                        <div class="mt-2 flex gap-6">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="radio" wire:model="cuali_lenguaje_inclusivo" value="Si" class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"><span>Sí</span></label>
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700"><input type="radio" wire:model="cuali_lenguaje_inclusivo" value="No" class="border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"><span>No</span></label>
                        </div>
                    </div>
                    <div>
                        <x-label for="cuali_estereotipo" value="Estereotipo" />
                        <select id="cuali_estereotipo" wire:model="cuali_estereotipo" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($estereotipos as $estereotipo)<option value="{{ $estereotipo }}">{{ $estereotipo }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <x-label for="cuali_violencia_temas_id" value="Tema de violencia" />
                        <select id="cuali_violencia_temas_id" wire:model="cuali_violencia_temas_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($violencia_temas as $tema)<option value="{{ $tema->id }}">{{ $tema->nombre }}</option>@endforeach
                        </select>
                    </div>
                    <div>
                        <x-label for="cuali_criterio_evaluacion" value="Criterio de evaluación" />
                        <select id="cuali_criterio_evaluacion" wire:model="cuali_criterio_evaluacion" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Selecciona...</option>
                            @foreach ($criterios_evaluacion as $criterio)<option value="{{ $criterio }}">{{ $criterio }}</option>@endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <x-label for="cuali_resumen" value="Resumen" />
                        <textarea id="cuali_resumen" wire:model.live.debounce.500ms="cuali_resumen" maxlength="255" rows="4" placeholder="Resumen del registro" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                        <x-input-error for="cuali_resumen" class="mt-1" />
                        <div class="text-end mt-1"><small class="text-muted">{{ strlen($cuali_resumen ?? '') }}/255 caracteres</small></div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cerrarCualitativos" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
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
        const CINE_INFO_KEY = 'media_view_digital_cine_info_anterior';
        const CINE_CUALITATIVOS_KEY = 'media_view_digital_cine_datos_cualitativos';

        $wire.on('cine-registro-guardado', (event) => {
            const datos = event.datos ?? event[0]?.datos ?? null;
            if (!datos) return;
            localStorage.setItem(CINE_INFO_KEY, JSON.stringify(datos));
        });

        $wire.on('cine-cualitativos-guardados', (event) => {
            const datos = event.datos ?? event[0]?.datos ?? null;
            if (!datos) return;
            localStorage.setItem(CINE_CUALITATIVOS_KEY, JSON.stringify(datos));
        });

        window.recuperarInfoAnteriorMedioCine = function() {
            const datosGuardados = localStorage.getItem(CINE_INFO_KEY);
            if (!datosGuardados) { alert('No hay información anterior guardada.'); return; }
            try { $wire.recuperarInfoAnterior(JSON.parse(datosGuardados)); } catch (error) { alert('No se pudo recuperar la información anterior.'); }
        };

        window.recuperarDatosCualitativosMedioCine = function() {
            const datosGuardados = localStorage.getItem(CINE_CUALITATIVOS_KEY);
            if (!datosGuardados) { alert('No hay datos cualitativos anteriores guardados.'); return; }
            try { $wire.recuperarDatosCualitativos(JSON.parse(datosGuardados)); } catch (error) { alert('No se pudieron recuperar los datos cualitativos.'); }
        };
    </script>
@endscript

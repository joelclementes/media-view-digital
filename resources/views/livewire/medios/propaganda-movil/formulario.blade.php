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
                    <button type="button" onclick="recuperarInfoAnteriorPropagandaMovil()" class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar info anterior
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.shared.persona')
                    @include('livewire.medios.propaganda-movil.partials.ubicacion')
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @include('livewire.medios.propaganda-movil.partials.publicacion')
                    @include('livewire.medios.propaganda-movil.partials.referencia')
                </div>

                @include('livewire.medios.shared.observaciones')
                @include('livewire.medios.shared.archivos')

                <div class="flex flex-col sm:flex-row justify-end gap-3">
                    {{-- @if ($registro_editando_id) --}}
                        <button type="button" wire:click="limpiarFormulario" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Limpiar
                        </button>
                    {{-- @endif --}}

                    <button type="button" wire:click="guardar" wire:loading.attr="disabled" class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">
                        <span wire:loading.remove wire:target="guardar">{{ $registro_editando_id ? 'Actualizar registro' : 'Guardar registro' }}</span>
                        <span wire:loading wire:target="guardar">Guardando...</span>
                    </button>
                </div>
            @endcan
        </div>

        <div class="mt-6 overflow-hidden rounded-lg bg-white shadow-md">
            <div class="border-b border-gray-200 p-4">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Registros capturados</h3>
                        <p class="text-sm text-gray-500">Consulta, filtra y administra la propaganda móvil.</p>
                    </div>

                    <button type="button" wire:click="alternarFiltrosTabla" class="inline-flex items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Filtros
                    </button>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5 {{ $mostrar_filtros_tabla ? '' : 'hidden' }}">
                    <div class="lg:col-span-2">
                        <x-label for="busqueda_tabla" value="Buscar registro" />
                        <x-input id="busqueda_tabla" type="text" wire:model.live.debounce.500ms="busqueda_tabla" placeholder="Buscar por sujeto, organización, razón social, unidad, placa..." class="w-full" />
                    </div>

                    <div>
                        <x-label for="fecha_inicio_registro" value="Fecha inicial" />
                        <x-input id="fecha_inicio_registro" type="date" wire:model.live="fecha_inicio_registro" class="w-full" />
                    </div>

                    <div>
                        <x-label for="fecha_fin_registro" value="Fecha final" />
                        <x-input id="fecha_fin_registro" type="date" wire:model.live="fecha_fin_registro" class="w-full" />
                    </div>

                    <div>
                        <x-label for="filtro_tipo_eleccion_id" value="Candidatura" />
                        <select id="filtro_tipo_eleccion_id" wire:model.live="filtro_tipo_eleccion_id" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Todas</option>
                            @foreach ($tipos_eleccion as $tipo)
                                <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-label for="cantidad_por_pagina" value="Mostrar" />
                        <select id="cantidad_por_pagina" wire:model.live="cantidad_por_pagina" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="10">10 registros</option>
                            <option value="25">25 registros</option>
                            <option value="50">50 registros</option>
                            <option value="100">100 registros</option>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="button" wire:click="limpiarFiltrosTabla" class="w-full rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            Limpiar filtros
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">ID</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Organización</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Sujeto</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Móvil</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Ubicación</th>
                            <th class="px-4 py-3 text-left font-semibold text-gray-700">Publicación</th>
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
                                    {{ $registro->unidad ?: 'Sin unidad' }}
                                    @if ($registro->numero)
                                        <span class="block text-xs text-gray-500">No. económico: {{ $registro->numero }}</span>
                                    @endif
                                    @if ($registro->placa)
                                        <span class="block text-xs text-gray-500">Placa: {{ $registro->placa }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $registro->municipio_nombre ?? 'Sin municipio' }}
                                    @if ($registro->distrito_nombre)
                                        <span class="block text-xs text-gray-500">{{ $registro->distrito_nombre }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    {{ $registro->tipo_publicidad_nombre ?? 'Sin tipo' }}
                                    @if ($registro->publicacion_medidas)
                                        <span class="block text-xs text-gray-500">{{ $registro->publicacion_medidas }}</span>
                                    @endif
                                    @if ($registro->publicacion_version)
                                        <span class="block text-xs text-gray-500">Versión: {{ $registro->publicacion_version }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        @can('crear_medio')
                                            <button type="button" title="Cualitativos" wire:click="abrirCualitativos({{ $registro->id }})" class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-file-diff class="h-5 w-5" />
                                            </button>
                                        @endcan

                                        <a href="{{ route('m-propaganda-movil-testigo', $registro->id) }}" target="_blank" title="Testigo" class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                            <x-lucide-file-type class="h-5 w-5" />
                                        </a>

                                        @can('editar_medio')
                                            <button type="button" wire:click="editar({{ $registro->id }})" title="Editar" class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-pencil class="h-5 w-5" />
                                            </button>
                                        @else
                                            <a href="{{ route('m-propaganda-movil-show', $registro->id) }}" title="Ver" class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                                <x-lucide-eye class="h-5 w-5" />
                                            </a>
                                        @endcan

                                        @can('eliminar_medio')
                                            <button type="button" title="Eliminar" wire:click="confirmarEliminacion({{ $registro->id }})" class="rounded-md p-1.5 text-red-600 hover:bg-red-50 hover:text-red-800">
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
                    <p class="mt-2 text-sm text-gray-600">
                        ¿Seguro que deseas eliminar <span class="font-semibold">{{ $registro_eliminar_referencia }}</span>?
                        Esta acción también eliminará las imágenes relacionadas.
                    </p>

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
                    <p class="text-sm text-gray-500">Registro de propaganda móvil #{{ $registro_cualitativo_id }}</p>
                </div>

                <button type="button" wire:click="cerrarCualitativos" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Volver a registros
                </button>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <h4 class="mb-4 text-lg font-semibold text-gray-800">Datos del registro</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Sujeto</span>{{ $registro_cualitativo['sujeto_nombre'] ?? 'Sin sujeto' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Organización</span>{{ $registro_cualitativo['organizacion_nombre'] ?? 'Sin organización' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Candidatura</span>{{ $registro_cualitativo['tipo_eleccion_nombre'] ?? 'Sin candidatura' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Razón social</span>{{ $registro_cualitativo['razon_social'] ?? 'Sin razón social' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Distrito</span>{{ $registro_cualitativo['distrito_nombre'] ?? 'Sin distrito' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Municipio</span>{{ $registro_cualitativo['municipio_nombre'] ?? 'Sin municipio' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Localidad</span>{{ $registro_cualitativo['localidad_nombre'] ?? 'Sin localidad' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Unidad</span>{{ $registro_cualitativo['unidad'] ?? 'Sin unidad' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Número económico</span>{{ $registro_cualitativo['numero'] ?? 'Sin número' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Placa</span>{{ $registro_cualitativo['placa'] ?? 'Sin placa' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Tipo</span>{{ $registro_cualitativo['tipo_publicidad_nombre'] ?? 'Sin tipo' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Medidas</span>{{ $registro_cualitativo['publicacion_medidas'] ?? 'Sin medidas' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Referencia</span>{{ $registro_cualitativo['referencia'] ?? 'Sin referencia' }}</div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <h4 class="text-lg font-semibold text-gray-800">Imágenes del registro</h4>
                </div>

                @if (count($imagenes_cualitativas) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($imagenes_cualitativas as $imagen)
                            <a href="{{ asset('storage/' . $imagen) }}" target="_blank" class="block overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                <img src="{{ asset('storage/' . $imagen) }}" alt="Imagen de propaganda móvil" class="h-48 w-full object-cover">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="rounded-md border border-gray-200 bg-gray-50 p-4 text-sm text-gray-500">Este registro no tiene imágenes cargadas.</p>
                @endif
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="mb-4">
                    <button type="button" onclick="recuperarCualitativosPropagandaMovil()" class="rounded-md border border-primary-300 bg-primary-50 px-3 py-2 text-xs font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar cualitativos anteriores
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-label for="cuali_valoracion" value="Valoración" />
                        <select id="cuali_valoracion" wire:model.live="cuali_valoracion" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Seleccionar valoración</option>
                            @foreach ($valoraciones as $valoracion)
                                <option value="{{ $valoracion }}">{{ $valoracion }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="cuali_valoracion" class="mt-1" />
                    </div>

                    <div>
                        <x-label for="cuali_lenguaje_inclusivo" value="Lenguaje inclusivo" />
                        <select id="cuali_lenguaje_inclusivo" wire:model.live="cuali_lenguaje_inclusivo" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Seleccionar</option>
                            @foreach ($opciones_si_no as $opcion)
                                <option value="{{ $opcion }}">{{ $opcion }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="cuali_lenguaje_inclusivo" class="mt-1" />
                    </div>

                    <div>
                        <x-label for="cuali_estereotipo" value="Estereotipo" />
                        <select id="cuali_estereotipo" wire:model.live="cuali_estereotipo" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Seleccionar estereotipo</option>
                            @foreach ($estereotipos as $estereotipo)
                                <option value="{{ $estereotipo }}">{{ $estereotipo }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="cuali_estereotipo" class="mt-1" />
                    </div>

                    <div>
                        <x-label for="cuali_violencia_temas_id" value="Tema de violencia" />
                        <select id="cuali_violencia_temas_id" wire:model.live="cuali_violencia_temas_id" class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                            <option value="">Seleccionar tema</option>
                            @foreach ($violencia_temas as $tema)
                                <option value="{{ $tema->id }}">{{ $tema->nombre }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="cuali_violencia_temas_id" class="mt-1" />
                    </div>

                    <div>
                        <x-label for="cuali_objetividad" value="Objetividad" />
                        <x-input id="cuali_objetividad" type="text" wire:model.live.debounce.500ms="cuali_objetividad" class="w-full" />
                        <x-input-error for="cuali_objetividad" class="mt-1" />
                    </div>

                    <div>
                        <x-label for="cuali_equidad" value="Equidad" />
                        <x-input id="cuali_equidad" type="text" wire:model.live.debounce.500ms="cuali_equidad" class="w-full" />
                        <x-input-error for="cuali_equidad" class="mt-1" />
                    </div>

                    <div>
                        <x-label for="cuali_calidad" value="Calidad" />
                        <x-input id="cuali_calidad" type="text" wire:model.live.debounce.500ms="cuali_calidad" class="w-full" />
                        <x-input-error for="cuali_calidad" class="mt-1" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="cerrarCualitativos" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Cancelar</button>
                    <button type="button" wire:click="guardarCualitativos" wire:loading.attr="disabled" class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white hover:bg-primary-700 disabled:opacity-60">Guardar cualitativos</button>
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        const PROPAGANDA_MOVIL_INFO_KEY = 'media_view_digital_propaganda_movil_info_anterior';
        const PROPAGANDA_MOVIL_CUALITATIVOS_KEY = 'media_view_digital_propaganda_movil_datos_cualitativos';

        $wire.on('propaganda-movil-registro-guardado', (event) => {
            const datos = event.datos ?? event[0]?.datos ?? null;
            if (!datos) return;
            localStorage.setItem(PROPAGANDA_MOVIL_INFO_KEY, JSON.stringify(datos));
        });

        $wire.on('propaganda-movil-cualitativos-guardados', (event) => {
            const datos = event.datos ?? event[0]?.datos ?? null;
            if (!datos) return;
            localStorage.setItem(PROPAGANDA_MOVIL_CUALITATIVOS_KEY, JSON.stringify(datos));
        });

        window.recuperarInfoAnteriorPropagandaMovil = function() {
            const datosGuardados = localStorage.getItem(PROPAGANDA_MOVIL_INFO_KEY);
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

        window.recuperarCualitativosPropagandaMovil = function() {
            const datosGuardados = localStorage.getItem(PROPAGANDA_MOVIL_CUALITATIVOS_KEY);
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

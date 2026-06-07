<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (!$mostrar_panel_cualitativo)
        <div class="bg-white overflow-hidden shadow-md sm:rounded-md p-4">

            <div class="mb-4 flex justify-start">
                <button type="button" onclick="recuperarInfoAnteriorMedioImpreso()"
                    class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                    Recuperar info anterior
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @include('livewire.medios.electronicos.partials.persona')
                @include('livewire.medios.impresos.partials.medio')
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @include('livewire.medios.impresos.partials.publicacion')
                @include('livewire.medios.electronicos.partials.autor')
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @include('livewire.medios.electronicos.partials.referencia')
                @include('livewire.medios.electronicos.partials.observaciones')
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @include('livewire.medios.electronicos.partials.archivos')
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

        <div class="mt-8 bg-white overflow-hidden shadow-md sm:rounded-md">
            <div class="border-b border-gray-200 p-4">
                <h3 class="text-lg font-semibold text-gray-800">Registros capturados</h3>
                <p class="text-sm text-gray-500">Consulta, filtra y revisa los registros de medios impresos.</p>

                <div class="mt-4">
                    <x-label for="busqueda_tabla" value="Buscar registro" />
                    <x-input id="busqueda_tabla" type="text" wire:model.live.debounce.500ms="busqueda_tabla"
                        placeholder="Buscar por sujeto, organización, medio, referencia, sección o página..."
                        class="w-full" />
                </div>
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
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2 text-primary-600">
                                        <button type="button" title="Cualitativos"
                                            wire:click="abrirCualitativos({{ $registro->id }})"
                                            class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                            <x-lucide-file-diff class="h-5 w-5" />
                                        </button>

                                        <a href="{{ route('m-impresos-testigo', $registro->id) }}" target="_blank"
                                            title="Testigo"
                                            class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                            <x-lucide-file-type class="h-5 w-5" />
                                        </a>

                                        <button type="button" title="Editar" wire:click="editar({{ $registro->id }})"
                                            class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                            <x-lucide-pencil class="h-5 w-5" />
                                        </button>

                                        <button type="button" title="Eliminar"
                                            wire:click="confirmarEliminacion({{ $registro->id }})"
                                            class="rounded-md p-1.5 text-red-600 hover:bg-red-50 hover:text-red-800">
                                            <x-lucide-trash-2 class="h-5 w-5" />
                                        </button>
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

    @if ($mostrar_panel_cualitativo)
        <div class="space-y-6">
            <div class="flex items-center justify-between">

                <div class="flex justify-start mb-5">
                    <button type="button" onclick="recuperarDatosCualitativosMedioImpreso()"
                        class="rounded-md border border-primary-300 bg-primary-50 px-4 py-2 text-sm font-medium text-primary-700 hover:bg-primary-100">
                        Recuperar datos cualitativos
                    </button>
                </div>

                <div>
                    <h3 class="text-xl font-semibold text-gray-800">Captura de datos cualitativos</h3>
                    <p class="text-sm text-gray-500">Registro #{{ $registro_cualitativo_id }}</p>
                </div>

                <button type="button" wire:click="cerrarCualitativos"
                    class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    Volver
                </button>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="rounded-lg bg-white p-5 shadow-md">
                    <h4 class="mb-4 text-lg font-semibold text-gray-800">
                        Datos del registro
                    </h4>

                    <dl class="space-y-1 text-sm">
                        <div>
                            <dt class="font-semibold text-gray-600">Org. política:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['organizacion'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Sujeto:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['sujeto'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Género del Sujeto:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['genero_sujeto'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Periodo:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['periodo'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Fecha Pub.:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['fecha'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Medio impreso:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['medio'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Tamaño Pub.:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['tamano'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Género:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['genero'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Sección:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['seccion'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Página:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['pagina'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Referencia:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['referencia'] ?? '' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-gray-600">Observ.:</dt>
                            <dd class="text-primary-800">{{ $registro_cualitativo['observaciones'] ?? '' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="rounded-lg bg-white p-5 shadow-md">
                    <h4 class="mb-4 text-lg font-semibold text-gray-800">
                        Imágenes relacionadas
                    </h4>

                    @if (count($imagenes_cualitativas) > 0)
                        @php
                            // dd($imagenes_cualitativas);
                        @endphp
                        <button type="button" wire:click="abrirModalImagen(0)"
                            class="block w-full overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                            <img src="{{ asset('storage/' . $imagenes_cualitativas[0]) }}"
                                alt="Imagen del registro" class="h-[360px] w-full object-contain">
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
                    @endif
                </div>
            </div>

            <div class="rounded-lg bg-white p-5 shadow-md">
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
                        <textarea id="cuali_resumen" wire:model="cuali_resumen" rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
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
                alert('No hay información anterior guardada en este navegador.');
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
                alert('No hay datos cualitativos guardados en este navegador.');
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

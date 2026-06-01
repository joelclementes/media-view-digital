<div>
    @if (session()->has('success'))
        <div class="mb-4 rounded-md border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-md sm:rounded-md p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @include('livewire.medios.electronicos.partials.persona')
            @include('livewire.medios.electronicos.partials.medio')
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @include('livewire.medios.electronicos.partials.publicacion')
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

            <x-button type="button" wire:click="guardar" wire:loading.attr="disabled" wire:target="guardar,archivos">
                <span wire:loading.remove wire:target="guardar">Guardar registro</span>
                <span wire:loading wire:target="guardar">Guardando...</span>
            </x-button>
        </div>
    </div>
    <div class="mt-8 bg-white overflow-hidden shadow-md sm:rounded-md p-4">
        <div class="mb-6">
            <div class="mb-4 flex flex-col gap-1">
                <h3 class="text-lg font-semibold text-gray-800">
                    Registros capturados
                </h3>

                <p class="text-sm text-gray-500">
                    Filtra por fecha de registro, candidatura o búsqueda general.
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <x-label for="fecha_inicio_registro" value="Fecha inicial" />

                        <x-input id="fecha_inicio_registro" type="date" wire:model.live="fecha_inicio_registro"
                            class="w-full" />
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
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-3 md:items-end">
                    <div class="md:col-span-2">
                        <x-label for="busqueda_tabla" value="Buscar" />

                        <x-input id="busqueda_tabla" type="text" wire:model.live.debounce.500ms="busqueda_tabla"
                            placeholder="Buscar por Id, sujeto, organización, portal, referencia o URL..." class="w-full" />
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

                <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-gray-500">
                        <span wire:loading.remove
                            wire:target="fecha_inicio_registro,fecha_fin_registro,filtro_tipo_eleccion_id,busqueda_tabla,cantidad_por_pagina">
                            Mostrando resultados según los filtros seleccionados.
                        </span>

                        <span wire:loading
                            wire:target="fecha_inicio_registro,fecha_fin_registro,filtro_tipo_eleccion_id,busqueda_tabla,cantidad_por_pagina"
                            class="text-blue-600">
                            Actualizando resultados...
                        </span>
                    </div>

                    <button type="button" wire:click="limpiarFiltrosTabla"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Limpiar filtros
                    </button>
                </div>
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
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha publicación</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Portal</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Archivos</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($registros as $registro)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">
                                {{ $registro->id }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ $registro->organizacion_nombre ?? 'Sin organización' }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ $registro->referencia ?: 'Sin referencia' }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ $registro->sujeto_nombre ?? 'Sin sujeto' }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ $registro->fecha ? \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') : 'Sin fecha' }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                {{ $registro->portal_nombre ?? 'Sin portal' }}
                            </td>

                            <td class="px-4 py-3 text-center text-gray-700">
                                {{ is_array($registro->archivos) ? count($registro->archivos) : 0 }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-3 text-blue-600">
                                    <button type="button" title="Editar" class="hover:text-blue-800">
                                        ✏️
                                    </button>

                                    <button type="button" title="Ver archivos" class="hover:text-blue-800">
                                        📄
                                    </button>

                                    <button type="button" title="Ver detalle" class="hover:text-blue-800">
                                        ℹ️
                                    </button>

                                    <button type="button" title="Eliminar" class="hover:text-red-600">
                                        🗑️
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                No hay registros para los filtros seleccionados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $registros->links() }}
        </div>
    </div>
</div>

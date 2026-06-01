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

            <span wire:loading.remove wire:target="guardar">
                {{ $registro_editando_id ? 'Actualizar registro' : 'Guardar registro' }}
            </span>

            <span wire:loading wire:target="guardar">
                {{ $registro_editando_id ? 'Actualizando...' : 'Guardando...' }}
            </span>
        </div>
    </div>

    <div class="mt-8 bg-white overflow-hidden shadow-md sm:rounded-md">
        <div class="border-b border-gray-200 p-4">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Registros capturados
                    </h3>

                    <p class="text-sm text-gray-500">
                        Consulta, filtra y revisa los registros de medios electrónicos.
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
                        placeholder="Buscar por Id, sujeto, organización, portal, referencia o URL..." class="w-full" />
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
                        <th class="w-20 px-4 py-3 text-left font-semibold text-gray-700">Id</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Organización</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Referencia</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Sujeto</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Fecha</th>
                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Portal</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($registros as $registro)
                        @php
                            $archivos = is_array($registro->archivos) ? $registro->archivos : [];
                            $primer_archivo = $archivos[0] ?? null;
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-800">
                                #{{ $registro->id }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                <div class="max-w-[220px] truncate"
                                    title="{{ $registro->organizacion_nombre ?? 'Sin organización' }}">
                                    {{ $registro->organizacion_nombre ?? 'Sin organización' }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                <div class="max-w-[260px] truncate"
                                    title="{{ $registro->referencia ?: 'Sin referencia' }}">
                                    {{ $registro->referencia ?: 'Sin referencia' }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                <div class="max-w-[220px] truncate"
                                    title="{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}">
                                    {{ $registro->sujeto_nombre ?? 'Sin sujeto' }}
                                </div>
                            </td>

                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">
                                {{ $registro->fecha ? \Carbon\Carbon::parse($registro->fecha)->format('d/m/Y') : 'Sin fecha' }}
                            </td>

                            <td class="px-4 py-3 text-gray-700">
                                <div class="max-w-[220px] truncate"
                                    title="{{ $registro->portal_nombre ?? 'Sin portal' }}">
                                    {{ $registro->portal_nombre ?? 'Sin portal' }}
                                </div>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2 text-primary-600">
                                    <button type="button" title="Cualitativos"
                                        class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                        <x-lucide-file-diff class="h-5 w-5" />
                                    </button>

                                    <button type="button" title="Testigo"
                                        class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                        <x-lucide-file-type class="h-5 w-5" />
                                    </button>

                                    <button type="button" title="Editar" wire:click="editar({{ $registro->id }})"
                                        class="rounded-md p-1.5 hover:bg-primary-50 hover:text-primary-800">
                                        <x-lucide-pencil class="h-5 w-5" />
                                    </button>

                                    <button type="button" title="Eliminar"
                                        class="rounded-md p-1.5 text-red-600 hover:bg-red-50 hover:text-red-800">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a49.025 49.025 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-gray-500">
                                No hay registros para los filtros seleccionados.
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
</div>

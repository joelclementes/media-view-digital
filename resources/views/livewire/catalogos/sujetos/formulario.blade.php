<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        @if (session()->has('success'))
            <div class="rounded-md bg-green-50 p-4 text-sm text-green-700 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="rounded-md bg-red-50 p-4 text-sm text-red-700 border border-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $sujeto_id ? 'Editar sujeto' : 'Registrar sujeto' }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Captura la información básica del sujeto.
                    </p>
                </div>

                @if ($sujeto_id)
                    <button
                        type="button"
                        wire:click="limpiarFormulario"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200"
                    >
                        Cancelar edición
                    </button>
                @endif
            </div>

            <form wire:submit.prevent="{{ $sujeto_id ? 'actualizar' : 'guardar' }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <div>
                    <x-label for="nombre" value="Nombre" />
                    <x-input
                        id="nombre"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="nombre"
                        autocomplete="off"
                    />
                    <x-input-error for="nombre" class="mt-2" />
                </div>

                <div>
                    <x-label for="genero_id" value="Género" />
                    <select
                        id="genero_id"
                        wire:model.defer="genero_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Selecciona un género</option>
                        @foreach ($generos as $genero)
                            <option value="{{ $genero->id }}">{{ $genero->nombre }}</option>
                        @endforeach
                    </select>
                    <x-input-error for="genero_id" class="mt-2" />
                </div>

                <div>
                    <x-label for="distrito_id" value="Distrito" />
                    <select
                        id="distrito_id"
                        wire:model.live="distrito_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Selecciona un distrito</option>
                        @foreach ($distritos as $distrito)
                            <option value="{{ $distrito->id }}">
                                {{ $distrito->clave }} - {{ $distrito->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-label for="municipio_id" value="Municipio" />
                    <select
                        id="municipio_id"
                        wire:model.defer="municipio_id"
                        @disabled(!$distrito_id)
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:text-gray-500"
                    >
                        @if (!$distrito_id)
                            <option value="">Selecciona primero un distrito</option>
                        @else
                            <option value="">Selecciona un municipio</option>
                            @foreach ($municipiosFormulario as $municipio)
                                <option value="{{ $municipio->id }}">{{ $municipio->nombre }}</option>
                            @endforeach
                        @endif
                    </select>
                    <x-input-error for="municipio_id" class="mt-2" />
                </div>

                <div>
                    <x-label for="partido_id" value="Partido/Asociación" />
                    <select
                        id="partido_id"
                        wire:model.defer="partido_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="">Selecciona una opción</option>
                        @foreach ($partidos as $partido)
                            <option value="{{ $partido->id }}">
                                {{ $partido->siglas }} - {{ $partido->nombre }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error for="partido_id" class="mt-2" />
                </div>

                <div class="flex items-end">
                    <x-button>
                        {{ $sujeto_id ? 'Actualizar' : 'Guardar' }}
                    </x-button>
                </div>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">Sujetos registrados</h2>
                    <p class="text-sm text-gray-500">
                        Busca por sujeto, municipio, partido o siglas.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-3">
                    <input
                        type="text"
                        wire:model.live.debounce.400ms="buscar"
                        placeholder="Buscar..."
                        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <select
                        wire:model.live="perPage"
                        class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                        <option value="10">10 registros</option>
                        <option value="50">50 registros</option>
                        <option value="100">100 registros</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto border border-gray-200 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Municipio
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Partido
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($sujetos as $sujeto)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <button
                                        type="button"
                                        wire:click="editar({{ $sujeto->id }})"
                                        class="text-indigo-600 hover:text-indigo-900 font-medium"
                                    >
                                        {{ $sujeto->nombre }}
                                    </button>
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $sujeto->municipio?->nombre ?? '—' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    @if ($sujeto->partido)
                                        {{ $sujeto->partido->siglas }} - {{ $sujeto->partido->nombre }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-sm text-center">
                                    <button
                                        type="button"
                                        wire:click="confirmarEliminar({{ $sujeto->id }})"
                                        class="inline-flex items-center text-red-600 hover:text-red-800"
                                        title="Eliminar"
                                    >
                                        <x-lucide-trash-2 class="h-5 w-5" />
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                                    No hay sujetos registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $sujetos->links() }}
            </div>
        </div>
    </div>

    @if ($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 px-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 bg-red-100 rounded-full p-2">
                        <x-lucide-trash-2 class="h-6 w-6 text-red-600" />
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">
                            Confirmar eliminación
                        </h3>

                        <p class="mt-2 text-sm text-gray-600">
                            ¿Deseas eliminar este sujeto? Esta acción no se podrá realizar si el sujeto ya está relacionado con registros de monitoreo.
                        </p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        type="button"
                        wire:click="cancelarEliminar"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50"
                    >
                        Cancelar
                    </button>

                    <button
                        type="button"
                        wire:click="eliminar"
                        class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700"
                    >
                        Sí, eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

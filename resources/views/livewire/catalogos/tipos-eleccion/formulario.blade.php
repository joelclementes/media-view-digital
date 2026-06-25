<div>
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
                        {{ $tipoEleccionId ? 'Editar tipo de elección' : 'Registrar tipo de elección' }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        Captura el nombre del tipo de elección.
                    </p>
                </div>

                @if ($tipoEleccionId)
                    <button
                        type="button"
                        wire:click="limpiarFormulario"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200"
                    >
                        Cancelar edición
                    </button>
                @endif
            </div>

            <form wire:submit.prevent="{{ $tipoEleccionId ? 'actualizar' : 'guardar' }}" class="space-y-4">
                <div>
                    <x-label for="nombre" value="Nombre" />

                    <x-input
                        id="nombre"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="nombre"
                        autocomplete="off"
                        autofocus
                    />

                    <x-input-error for="nombre" class="mt-2" />
                </div>

                <div>
                    <x-button>
                        {{ $tipoEleccionId ? 'Actualizar' : 'Guardar' }}
                    </x-button>
                </div>
            </form>
        </div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800">
                        Tipos de elección registrados
                    </h2>

                    <p class="text-sm text-gray-500">
                        Busca y administra los tipos de elección.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-3">
                    <input
                        type="text"
                        wire:model.live.debounce.400ms="buscar"
                        placeholder="Buscar por nombre..."
                        class="border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                    >

                    <select
                        wire:model.live="perPage"
                        class="border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
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

                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($tiposEleccion as $tipoEleccion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm">
                                    <button
                                        type="button"
                                        wire:click="editar({{ $tipoEleccion->id }})"
                                        class="text-primary-600 hover:text-primary-900 font-medium"
                                    >
                                        {{ $tipoEleccion->nombre }}
                                    </button>
                                </td>

                                <td class="px-4 py-3 text-sm text-center">
                                    <button
                                        type="button"
                                        wire:click="confirmarEliminar({{ $tipoEleccion->id }})"
                                        class="inline-flex items-center justify-center rounded-full p-2 text-red-600 hover:bg-red-50 hover:text-red-800 transition"
                                        title="Eliminar"
                                    >
                                        <x-lucide-trash-2 class="h-5 w-5" />
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-4 py-6 text-center text-sm text-gray-500">
                                    No hay tipos de elección registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tiposEleccion->links() }}
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
                            Eliminar tipo de elección
                        </h3>

                        <p class="mt-2 text-sm text-gray-600">
                            ¿Deseas eliminar este tipo de elección?
                        </p>

                        <p class="mt-3 text-sm text-gray-600">
                            Esta acción no se podrá deshacer. Si el registro está relacionado con monitoreos, el sistema no permitirá eliminarlo.
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

@script
<script>
    $wire.on('scroll-arriba', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
@endscript
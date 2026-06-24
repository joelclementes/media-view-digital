<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tipos de elección
        </h2>
    </x-slot>

    <div
        x-data="{
            modalEliminar: false,
            deleteUrl: '',
            nombreEliminar: '',

            abrirModalEliminar(url, nombre) {
                this.deleteUrl = url;
                this.nombreEliminar = nombre;
                this.modalEliminar = true;
            },

            cerrarModalEliminar() {
                this.modalEliminar = false;
                this.deleteUrl = '';
                this.nombreEliminar = '';
            }
        }"
        class="py-6"
    >
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-800">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <form
                    method="POST"
                    action="{{ $tipoEleccionEdit ? route('cat-tipos-eleccion.update', $tipoEleccionEdit) : route('cat-tipos-eleccion.store') }}"
                    class="space-y-4"
                >
                    @csrf

                    @if ($tipoEleccionEdit)
                        @method('PUT')
                    @endif

                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            id="nombre"
                            value="{{ old('nombre', $tipoEleccionEdit?->nombre) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            required
                            autofocus
                        >
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700"
                        >
                            {{ $tipoEleccionEdit ? 'Actualizar' : 'Guardar' }}
                        </button>

                        @if ($tipoEleccionEdit)
                            <a
                                href="{{ route('cat-tipos-eleccion.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300"
                            >
                                Cancelar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="GET" action="{{ route('cat-tipos-eleccion.index') }}" class="mb-4">
                    <label for="buscar" class="block text-sm font-medium text-gray-700">
                        Buscar tipo de elección
                    </label>

                    <div class="mt-1 flex gap-2">
                        <input
                            type="text"
                            name="buscar"
                            id="buscar"
                            value="{{ $buscar }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Buscar por nombre..."
                        >

                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                        >
                            Buscar
                        </button>

                        @if ($buscar)
                            <a
                                href="{{ route('cat-tipos-eleccion.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300"
                            >
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    Nombre
                                </th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($tiposEleccion as $tipoEleccion)
                                <tr>
                                    <td class="px-4 py-3">
                                        <a
                                            href="{{ route('cat-tipos-eleccion.edit', $tipoEleccion) }}"
                                            class="text-primary-600 hover:text-primary-900 font-medium"
                                        >
                                            {{ $tipoEleccion->nombre }}
                                        </a>
                                    </td>

                                    <td class="px-4 py-3 text-center">
                                        <button
                                            type="button"
                                            x-on:click="abrirModalEliminar(
                                                '{{ route('cat-tipos-eleccion.destroy', $tipoEleccion) }}',
                                                @js($tipoEleccion->nombre)
                                            )"
                                            class="inline-flex items-center justify-center rounded-full p-2 text-red-600 hover:bg-red-50 hover:text-red-800 transition"
                                            title="Eliminar"
                                        >
                                            <x-lucide-trash-2 class="h-5 w-5" />
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-4 text-center text-gray-500">
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

        <div
            x-cloak
            x-show="modalEliminar"
            x-transition.opacity
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
            aria-labelledby="modal-title"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="modalEliminar"
                x-transition
                x-on:click.outside="cerrarModalEliminar()"
                x-on:keydown.escape.window="cerrarModalEliminar()"
                class="w-full max-w-md rounded-lg bg-white shadow-xl"
            >
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 text-2xl">
                            🗑️
                        </div>

                        <div>
                            <h3 id="modal-title" class="text-lg font-semibold text-gray-900">
                                Eliminar tipo de elección
                            </h3>

                            <p class="mt-2 text-sm text-gray-600">
                                Estás a punto de eliminar:
                            </p>

                            <p class="mt-2 font-semibold text-gray-900" x-text="nombreEliminar"></p>

                            <p class="mt-3 text-sm text-gray-600">
                                Esta acción no se podrá deshacer. Si el registro está relacionado con monitoreos, el sistema no permitirá eliminarlo.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse gap-2 bg-gray-50 px-6 py-4 sm:flex-row sm:justify-end">
                    <button
                        type="button"
                        x-on:click="cerrarModalEliminar()"
                        class="inline-flex justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    >
                        Cancelar
                    </button>

                    <form method="POST" x-bind:action="deleteUrl">
                        @csrf
                        @method('DELETE')

                        <button
                            type="submit"
                            class="inline-flex justify-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700"
                        >
                            Sí, eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Roles y permisos
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-6 rounded-xl bg-green-100 border border-green-300 text-green-800 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl bg-red-100 border border-red-300 text-red-800 px-4 py-3">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white rounded-2xl shadow p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            Roles
                        </h2>

                        <p class="text-sm text-gray-500">
                            Crea roles y asígnales permisos.
                        </p>
                    </div>

                    <form action="{{ route('roles.store') }}" method="POST" class="space-y-5 mb-8">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Nombre del rol
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                                placeholder="Ejemplo: Capturista"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Permisos
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto border rounded-xl p-3 bg-gray-50">
                                @forelse ($permissions as $permission)
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                        >

                                        <span>{{ $permission->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500">
                                        No hay permisos registrados.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-2 rounded-xl shadow"
                            >
                                Crear rol
                            </button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="text-left px-4 py-3">Rol</th>
                                    <th class="text-left px-4 py-3">Permisos</th>
                                    <th class="text-center px-4 py-3">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($roles as $role)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-semibold text-gray-800">
                                            {{ $role->name }}
                                        </td>

                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap gap-1">
                                                @forelse ($role->permissions as $permission)
                                                    <span class="text-xs bg-primary-100 text-primary-700 px-2 py-1 rounded-full">
                                                        {{ $permission->name }}
                                                    </span>
                                                @empty
                                                    <span class="text-xs text-gray-400">
                                                        Sin permisos
                                                    </span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <button
                                                type="button"
                                                onclick="abrirModal('modal-role-{{ $role->id }}')"
                                                class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded-lg"
                                            >
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                            No hay roles registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">
                            Permisos
                        </h2>

                        <p class="text-sm text-gray-500">
                            Crea y administra permisos del sistema.
                        </p>
                    </div>

                    <form action="{{ route('permisos.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3 mb-8">
                        @csrf

                        <input
                            type="text"
                            name="name"
                            class="flex-1 rounded-xl border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                            placeholder="Ejemplo: registrar_medios_impresos"
                            required
                        >

                        <button
                            type="submit"
                            class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-2 rounded-xl shadow"
                        >
                            Crear permiso
                        </button>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="text-left px-4 py-3">Permiso</th>
                                    <th class="text-center px-4 py-3">Acciones</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($permissions as $permission)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-800">
                                            {{ $permission->name }}
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            <button
                                                type="button"
                                                onclick="abrirModal('modal-permission-{{ $permission->id }}')"
                                                class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded-lg"
                                            >
                                                Editar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-4 py-6 text-center text-gray-500">
                                            No hay permisos registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @foreach ($roles as $role)
        <div id="modal-role-{{ $role->id }}" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center px-4">
            <div class="bg-white w-full max-w-3xl rounded-2xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            Editar rol
                        </h3>

                        <p class="text-sm text-gray-500">
                            Modifica el nombre y los permisos asignados.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="cerrarModal('modal-role-{{ $role->id }}')"
                        class="text-gray-500 hover:text-gray-800 text-2xl"
                    >
                        &times;
                    </button>
                </div>

                <form action="{{ route('roles.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">
                                Nombre del rol
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ $role->name }}"
                                class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Permisos
                            </label>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-72 overflow-y-auto border rounded-xl p-3 bg-gray-50">
                                @foreach ($permissions as $permission)
                                    <label class="flex items-center gap-2 text-sm text-gray-700">
                                        <input
                                            type="checkbox"
                                            name="permissions[]"
                                            value="{{ $permission->name }}"
                                            @checked($role->hasPermissionTo($permission->name))
                                            class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                                        >

                                        <span>{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between mt-6">
                        <button
                            type="button"
                            onclick="document.getElementById('delete-role-{{ $role->id }}').submit()"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded-xl shadow"
                        >
                            Eliminar
                        </button>

                        <button
                            type="submit"
                            class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-2 rounded-xl shadow"
                        >
                            Actualizar
                        </button>
                    </div>
                </form>

                <form id="delete-role-{{ $role->id }}" action="{{ route('roles.destroy', $role) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    @endforeach

    @foreach ($permissions as $permission)
        <div id="modal-permission-{{ $permission->id }}" class="hidden fixed inset-0 z-50 bg-black/50 items-center justify-center px-4">
            <div class="bg-white w-full max-w-xl rounded-2xl shadow-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            Editar permiso
                        </h3>

                        <p class="text-sm text-gray-500">
                            Modifica el nombre del permiso.
                        </p>
                    </div>

                    <button
                        type="button"
                        onclick="cerrarModal('modal-permission-{{ $permission->id }}')"
                        class="text-gray-500 hover:text-gray-800 text-2xl"
                    >
                        &times;
                    </button>
                </div>

                <form action="{{ route('permisos.update', $permission) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Nombre del permiso
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ $permission->name }}"
                        class="w-full rounded-xl border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                        required
                    >

                    <div class="flex justify-between mt-6">
                        <button
                            type="button"
                            onclick="document.getElementById('delete-permission-{{ $permission->id }}').submit()"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded-xl shadow"
                        >
                            Eliminar
                        </button>

                        <button
                            type="submit"
                            class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-2 rounded-xl shadow"
                        >
                            Actualizar
                        </button>
                    </div>
                </form>

                <form id="delete-permission-{{ $permission->id }}" action="{{ route('permisos.destroy', $permission) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    @endforeach

    <script>
        function abrirModal(id) {
            const modal = document.getElementById(id);

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function cerrarModal(id) {
            const modal = document.getElementById(id);

            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>
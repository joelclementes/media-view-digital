<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administración de usuarios
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

            <div class="bg-white rounded-2xl shadow p-6">

                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Usuarios
                    </h2>

                    <p class="text-sm text-gray-500">
                        Crea usuarios y asígnales un rol del sistema.
                    </p>
                </div>

                <form action="{{ route('usuarios.store') }}" method="POST"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Usuario / correo</label>
                        <input type="text" name="email" value="{{ old('email') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Rol</label>
                        <select name="role"
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                            required>
                            <option value="">Seleccione</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role') === $role->name)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Contraseña</label>
                        <input type="password" name="password"
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                            required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation"
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                            required>
                    </div>

                    <div class="md:col-span-2 lg:col-span-5 flex justify-end">
                        <button type="submit"
                            class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-2 rounded-lg shadow">
                            Guardar usuario
                        </button>
                    </div>
                </form>

                <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-4">
                    <div class="w-full md:w-80">
                        <label for="usuariosFiltro" class="block text-sm font-medium mb-1 text-gray-700">
                            Buscar
                        </label>
                        <input type="text" id="usuariosFiltro" placeholder="Nombre, usuario o rol..."
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <div class="w-full md:w-48">
                        <label for="usuariosPorPagina" class="block text-sm font-medium mb-1 text-gray-700">
                            Registros por página
                        </label>
                        <select id="usuariosPorPagina"
                            class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b bg-gray-100">
                                <th class="text-left px-4 py-3">Nombre</th>
                                <th class="text-left px-4 py-3">Usuario</th>
                                <th class="text-left px-4 py-3">Rol</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-700">Activo</th>
                                <th class="text-center px-4 py-3">Acciones</th>
                            </tr>
                        </thead>

                        <tbody id="usuariosTabla">
                            @forelse ($users as $user)
                                @php
                                    $rolUsuario = $user->getRoleNames()->first() ?? 'Sin rol';
                                @endphp

                                <tr class="border-b hover:bg-gray-50 usuario-row"
                                    data-search="{{ mb_strtolower($user->name . ' ' . $user->email . ' ' . $rolUsuario) }}">
                                    <td class="px-4 py-3 font-medium text-gray-800">
                                        {{ $user->name }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-700">
                                        {{ $user->email }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex rounded-full bg-primary-100 px-3 py-1 text-xs font-semibold text-primary-700">
                                            {{ $rolUsuario }}
                                        </span>
                                    </td>

<td class="px-4 py-3">
    <livewire:usuarios.estado-switch
        :user="$user"
        :key="'estado-'.$user->id"
    />
</td>

                                    <td class="px-4 py-3 text-center">
                                        <button type="button" onclick="abrirModal('modal-user-{{ $user->id }}')"
                                            class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded-lg">
                                            Editar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                        No hay usuarios registrados.
                                    </td>
                                </tr>
                            @endforelse

                            <tr id="usuariosSinResultados" class="hidden">
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                    No se encontraron usuarios con ese filtro.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mt-4">
                    <p id="usuariosResumen" class="text-sm text-gray-500"></p>

                    <div class="flex items-center justify-end gap-2">
                        <button type="button" id="usuariosAnterior"
                            class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                            Anterior
                        </button>

                        <span id="usuariosPagina" class="text-sm text-gray-600"></span>

                        <button type="button" id="usuariosSiguiente"
                            class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed">
                            Siguiente
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @foreach ($users as $user)
        <div id="modal-user-{{ $user->id }}"
            class="hidden fixed inset-0 bg-black/50 z-50 items-center justify-center px-4">
            <div class="bg-white w-full max-w-2xl rounded-xl shadow-lg p-6">

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Editar usuario</h2>
                        <p class="text-sm text-gray-500">Actualiza datos, rol o contraseña.</p>
                    </div>

                    <button type="button" onclick="cerrarModal('modal-user-{{ $user->id }}')"
                        class="text-gray-500 hover:text-gray-800 text-2xl">
                        &times;
                    </button>
                </div>

                <form action="{{ route('usuarios.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium mb-1">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Usuario / correo</label>
                            <input type="text" name="email" value="{{ old('text', $user->email) }}"
                                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Rol</label>
                            <select name="role"
                                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500"
                                required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Nueva contraseña</label>
                            <input type="password" name="password"
                                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Confirmar contraseña</label>
                            <input type="password" name="password_confirmation"
                                class="w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                            class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-2 rounded-lg shadow">
                            Actualizar usuario
                        </button>
                    </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            const filtroInput = document.getElementById('usuariosFiltro');
            const porPaginaSelect = document.getElementById('usuariosPorPagina');
            const resumen = document.getElementById('usuariosResumen');
            const paginaTexto = document.getElementById('usuariosPagina');
            const btnAnterior = document.getElementById('usuariosAnterior');
            const btnSiguiente = document.getElementById('usuariosSiguiente');
            const sinResultados = document.getElementById('usuariosSinResultados');

            const filas = Array.from(document.querySelectorAll('.usuario-row'));

            let paginaActual = 1;

            function obtenerFilasFiltradas() {
                const filtro = filtroInput.value.trim().toLowerCase();

                if (!filtro) {
                    return filas;
                }

                return filas.filter((fila) => {
                    return fila.dataset.search.includes(filtro);
                });
            }

            function renderizarTabla() {
                const porPagina = parseInt(porPaginaSelect.value, 10);
                const filtradas = obtenerFilasFiltradas();
                const total = filtradas.length;
                const totalPaginas = Math.max(Math.ceil(total / porPagina), 1);

                if (paginaActual > totalPaginas) {
                    paginaActual = totalPaginas;
                }

                const inicio = (paginaActual - 1) * porPagina;
                const fin = inicio + porPagina;

                filas.forEach((fila) => {
                    fila.classList.add('hidden');
                });

                filtradas.slice(inicio, fin).forEach((fila) => {
                    fila.classList.remove('hidden');
                });

                sinResultados.classList.toggle('hidden', total > 0);

                if (total === 0) {
                    resumen.textContent = 'Mostrando 0 registros';
                    paginaTexto.textContent = 'Página 0 de 0';
                    btnAnterior.disabled = true;
                    btnSiguiente.disabled = true;
                    return;
                }

                resumen.textContent = `Mostrando ${inicio + 1}-${Math.min(fin, total)} de ${total} registros`;
                paginaTexto.textContent = `Página ${paginaActual} de ${totalPaginas}`;

                btnAnterior.disabled = paginaActual === 1;
                btnSiguiente.disabled = paginaActual === totalPaginas;
            }

            filtroInput.addEventListener('input', function() {
                paginaActual = 1;
                renderizarTabla();
            });

            porPaginaSelect.addEventListener('change', function() {
                paginaActual = 1;
                renderizarTabla();
            });

            btnAnterior.addEventListener('click', function() {
                if (paginaActual > 1) {
                    paginaActual--;
                    renderizarTabla();
                }
            });

            btnSiguiente.addEventListener('click', function() {
                const porPagina = parseInt(porPaginaSelect.value, 10);
                const totalPaginas = Math.ceil(obtenerFilasFiltradas().length / porPagina);

                if (paginaActual < totalPaginas) {
                    paginaActual++;
                    renderizarTabla();
                }
            });

            renderizarTabla();
        });
    </script>
</x-app-layout>

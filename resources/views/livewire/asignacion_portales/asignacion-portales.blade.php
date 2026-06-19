<div>
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">
            Asignación de Páginas a Capturistas
        </h1>

        <p class="mt-1 text-sm text-gray-600">
            Selecciona un capturista y marca las páginas que podrá capturar.
        </p>
    </div>

    @if (session('success'))
        <div
            class="mb-4 rounded border-l-4 border-green-500 bg-green-100 p-4 text-green-700"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
        >
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            class="mb-4 rounded border-l-4 border-red-500 bg-red-100 p-4 text-red-700"
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
        >
            {{ session('error') }}
        </div>
    @endif

    <div
        x-data="{ show: false }"
        x-on:asignacion-guardada.window="show = true; setTimeout(() => show = false, 1500)"
        x-show="show"
        x-transition
        class="fixed bottom-4 right-4 z-50 flex items-center space-x-2 rounded-lg bg-green-500 px-4 py-2 text-white shadow-lg"
        style="display: none;"
    >
        <x-lucide-check class="h-5 w-5" />
        <span>Cambios guardados</span>
    </div>

    <div class="rounded-lg bg-white p-6 shadow-md">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="lg:col-span-1">
                <label class="mb-3 block text-sm font-medium text-gray-700">
                    Lista de Capturistas <span class="text-red-500">*</span>
                </label>

                <div class="max-h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50">
                    <div class="space-y-1 p-2">
                        @forelse ($capturistas as $capturista)
                            <button
                                type="button"
                                wire:click="$set('capturista_id', '{{ $capturista->id }}')"
                                class="w-full rounded-md p-3 text-left transition-all duration-200
                                    {{ $capturista_id == $capturista->id
                                        ? 'scale-[1.02] transform bg-primary-700 text-white shadow-md'
                                        : 'border border-gray-200 bg-white text-gray-700 hover:bg-gray-100 hover:shadow'
                                    }}"
                            >
                                <div class="flex items-center">
                                    <div class="mr-3 flex-shrink-0">
                                        @if ($capturista->profile_photo_path)
                                            <img
                                                class="h-10 w-10 rounded-full object-cover"
                                                src="{{ asset('storage/' . $capturista->profile_photo_path) }}"
                                                alt="{{ $capturista->name }}"
                                            >
                                        @else
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-full
                                                    {{ $capturista_id == $capturista->id ? 'bg-primary-800' : 'bg-gray-300' }}"
                                            >
                                                <span
                                                    class="text-sm font-medium
                                                        {{ $capturista_id == $capturista->id ? 'text-white' : 'text-gray-600' }}"
                                                >
                                                    {{ strtoupper(substr($capturista->name, 0, 2)) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <p
                                            class="truncate text-sm font-medium
                                                {{ $capturista_id == $capturista->id ? 'text-white' : 'text-gray-900' }}"
                                        >
                                            {{ $capturista->name }}
                                        </p>

                                        <p
                                            class="truncate text-xs
                                                {{ $capturista_id == $capturista->id ? 'text-primary-100' : 'text-gray-500' }}"
                                        >
                                            {{ $capturista->email }}
                                        </p>
                                    </div>

                                    @if ($capturista_id == $capturista->id)
                                        <x-lucide-check class="ml-2 h-5 w-5 text-white" />
                                    @endif
                                </div>

                                @php
                                    $cantidadAsignados = App\Models\CapturistaPortalInternet::where('user_id', $capturista->id)->count();
                                @endphp

                                @if ($cantidadAsignados > 0)
                                    <div
                                        class="mt-2 text-xs
                                            {{ $capturista_id == $capturista->id ? 'text-primary-100' : 'text-gray-500' }}"
                                    >
                                        {{ $cantidadAsignados }} página(s) asignada(s)
                                    </div>
                                @endif
                            </button>
                        @empty
                            <div class="py-8 text-center text-gray-400">
                                <x-lucide-users class="mx-auto mb-3 h-12 w-12" />
                                <p>No hay capturistas disponibles.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <p class="mt-2 text-xs text-gray-500">
                    <span class="mr-1 inline-block h-3 w-3 rounded bg-primary-700"></span>
                    Capturista seleccionado actualmente.
                </p>
            </div>

            <div class="lg:col-span-2">
                @if ($capturista_id)
                    <div>
                        <div class="mb-4 flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">
                                Páginas disponibles <span class="text-red-500">*</span>
                            </label>

                            <div class="flex space-x-2">
                                <button
                                    type="button"
                                    wire:click="seleccionarTodos"
                                    class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-700 transition-colors hover:bg-gray-200"
                                >
                                    Seleccionar todas
                                </button>

                                <button
                                    type="button"
                                    wire:click="deseleccionarTodos"
                                    class="rounded bg-gray-100 px-2 py-1 text-xs text-gray-700 transition-colors hover:bg-gray-200"
                                >
                                    Deseleccionar todas
                                </button>
                            </div>
                        </div>

                        <div class="max-h-96 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-2">
                            <div class="space-y-2">
                                @forelse ($portales as $portal)
                                    @php
                                        $isAsignado = in_array((string) $portal->id, $portalesSeleccionados);
                                        $isDisabled = $portal->asignado_a_otro;

                                        $asignadoA = null;

                                        if ($isDisabled) {
                                            $asignacion = $portalesAsignadosOtros->firstWhere('portal_internet_id', $portal->id);
                                            $asignadoA = $asignacion ? $asignacion->capturista->name : null;
                                        }

                                        $bgClass = $isAsignado
                                            ? 'bg-green-50'
                                            : ($isDisabled ? 'bg-gray-100' : 'bg-white');

                                        $borderClass = $isAsignado
                                            ? 'border-green-300'
                                            : ($isDisabled ? 'border-gray-300' : 'border-gray-200');
                                    @endphp

                                    <div
                                        class="mb-2 flex items-center rounded-md border p-3 transition-colors
                                            {{ $bgClass }}
                                            {{ $borderClass }}
                                            {{ $isDisabled ? 'opacity-75' : 'hover:border-primary-700' }}"
                                    >
                                        <input
                                            type="checkbox"
                                            wire:model.live="portalesSeleccionados"
                                            value="{{ $portal->id }}"
                                            id="portal-{{ $portal->id }}"
                                            class="h-4 w-4 rounded border-gray-300 text-primary-700 focus:ring-primary-700"
                                            {{ $isDisabled ? 'disabled' : '' }}
                                        >

                                        <div class="ml-3 flex-1">
                                            <label
                                                for="portal-{{ $portal->id }}"
                                                class="block text-sm text-gray-700 cursor-{{ $isDisabled ? 'not-allowed' : 'pointer' }}"
                                            >
                                                <span class="font-medium">
                                                    {{ $portal->url }}
                                                </span>

                                                <span class="ml-2 text-xs text-gray-500">
                                                    {{ $portal->nombre }}
                                                </span>
                                            </label>

                                            <div class="mt-1 text-xs text-gray-500">
                                                {{ $portal->ciudad }} · {{ $portal->tipo }}
                                            </div>

                                            @if ($isAsignado && $capturista_id)
                                                <div class="mt-1 text-xs text-green-600">
                                                    <span class="font-medium">Asignada a:</span>
                                                    {{ $capturistas->firstWhere('id', $capturista_id)->name }}
                                                </div>
                                            @elseif ($isDisabled && $asignadoA)
                                                <div class="mt-1 text-xs text-gray-500">
                                                    <span class="font-medium">Asignada a:</span>
                                                    {{ $asignadoA }}
                                                </div>
                                            @endif
                                        </div>

                                        @if ($isAsignado)
                                            <span class="rounded-full bg-green-200 px-2 py-1 text-xs text-green-800">
                                                Actual
                                            </span>
                                        @elseif ($isDisabled)
                                            <span class="rounded-full bg-gray-200 px-2 py-1 text-xs text-gray-600">
                                                Ocupada
                                            </span>
                                        @endif
                                    </div>
                                @empty
                                    <div class="py-8 text-center text-gray-400">
                                        <x-lucide-inbox class="mx-auto mb-3 h-12 w-12" />
                                        <p>No hay páginas disponibles.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <p class="mt-2 text-xs text-gray-500">
                            <span class="mr-1 inline-block h-3 w-3 rounded border border-green-300 bg-green-100"></span>
                            Páginas asignadas al capturista actual
                            <br>

                            <span class="mr-1 mt-1 inline-block h-3 w-3 rounded border border-gray-300 bg-white"></span>
                            Páginas disponibles
                            <br>

                            <span class="mr-1 mt-1 inline-block h-3 w-3 rounded border border-gray-300 bg-gray-100"></span>
                            Páginas asignadas a otros capturistas
                        </p>
                    </div>
                @else
                    <div class="rounded-lg border border-gray-200 bg-gray-50 py-12 text-center text-gray-400">
                        <x-lucide-users class="mx-auto mb-4 h-16 w-16" />

                        <p class="text-lg">
                            Selecciona un capturista de la lista para ver las páginas disponibles.
                        </p>

                        <p class="mt-2 text-sm">
                            Haz clic en cualquier capturista de la columna izquierda para comenzar.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
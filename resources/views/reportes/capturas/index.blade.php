<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de capturas por usuario
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="rounded-md bg-red-50 p-4 text-sm text-red-700 border border-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-800">
                        Consulta de capturas
                    </h2>

                    <p class="text-sm text-gray-500">
                        Selecciona el medio y el rango de fechas para consultar cuántas capturas realizó cada capturista.
                    </p>
                </div>

                <form method="GET" action="{{ route('reportes.capturas.index') }}"
                      class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div>
                        <x-label for="tipo" value="Tipo de medio" />

                        <select
                            id="tipo"
                            name="tipo"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500"
                            required
                        >
                            <option value="">Seleccionar tipo</option>
                            <option value="monitoreo_medios_electronicos" @selected(request('tipo') === 'monitoreo_medios_electronicos')>Medios electrónicos</option>
                            <option value="monitoreo_medios_impresos" @selected(request('tipo') === 'monitoreo_medios_impresos')>Medios impresos</option>
                            <option value="monitoreo_radio" @selected(request('tipo') === 'monitoreo_radio')>Radio</option>
                            <option value="monitoreo_television" @selected(request('tipo') === 'monitoreo_television')>Televisión</option>
                            <option value="monit_soportes_promocionales" @selected(request('tipo') === 'monit_soportes_promocionales')>Soportes promocionales</option>
                            <option value="monitoreo_propaganda_movil" @selected(request('tipo') === 'monitoreo_propaganda_movil')>Propaganda movil</option>
                            <option value="monitoreo_cine" @selected(request('tipo') === 'monitoreo_cine')>Cine</option>
                        </select>
                    </div>

                    <div>
                        <x-label for="fecha_inicial" value="Fecha inicial" />

                        <x-input
                            id="fecha_inicial"
                            name="fecha_inicial"
                            type="date"
                            class="mt-1 block w-full"
                            value="{{ request('fecha_inicial') }}"
                        />
                    </div>

                    <div>
                        <x-label for="fecha_final" value="Fecha final" />

                        <x-input
                            id="fecha_final"
                            name="fecha_final"
                            type="date"
                            class="mt-1 block w-full"
                            value="{{ request('fecha_final') }}"
                        />
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            name="consultar"
                            value="1"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Consultar
                        </button>
                    </div>
                </form>
            </div>

            @if ($consultado)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">
                                Resultados
                            </h2>

                            <p class="text-sm text-gray-500">
                                Total de capturas encontradas: <strong>{{ $totalCapturas }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Capturista
                                    </th>

                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Usuario / correo
                                    </th>

                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Capturas
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($resultados as $resultado)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-800">
                                            {{ $resultado->nombre }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-gray-700">
                                            {{ $resultado->email }}
                                        </td>

                                        <td class="px-4 py-3 text-sm text-center">
                                            <span class="inline-flex rounded-full bg-primary-100 px-3 py-1 text-xs font-semibold text-primary-700">
                                                {{ $resultado->total }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center text-sm text-gray-500">
                                            No hay capturistas registrados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-700 text-right">
                                        Total
                                    </td>
                                    <td class="px-4 py-3 text-sm font-bold text-gray-900 text-center">
                                        {{ $totalCapturas }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a
                            href="{{ route('reportes.capturas.pdf', request()->only('tipo', 'fecha_inicial', 'fecha_final')) }}"
                            target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Exportar PDF
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

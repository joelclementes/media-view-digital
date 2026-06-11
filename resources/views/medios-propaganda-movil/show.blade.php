<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Propaganda móvil #{{ $registro->id }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="rounded-xl bg-white p-6 shadow-md">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">Detalle del registro</h3>
                    <a href="{{ route('m-propaganda-movil-index') }}" class="rounded-md border border-gray-300 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        Volver
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Sujeto</span>{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Organización</span>{{ $registro->organizacion_nombre ?? 'Sin organización' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Periodo</span>{{ $registro->periodo_nombre ?? 'Sin periodo' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Etapa</span>{{ $registro->etapa_sujeto ?? 'Sin etapa' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Candidatura</span>{{ $registro->tipo_eleccion_nombre ?? 'Sin candidatura' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Razón social</span>{{ $registro->razon_social ?? 'Sin razón social' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Distrito</span>{{ $registro->distrito_nombre ?? 'Sin distrito' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Municipio</span>{{ $registro->municipio_nombre ?? 'Sin municipio' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Localidad</span>{{ $registro->localidad_nombre ?? 'Sin localidad' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Sección</span>{{ $registro->seccion ?? 'Sin sección' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Latitud</span>{{ $registro->latitud ?? 'Sin latitud' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Longitud</span>{{ $registro->longitud ?? 'Sin longitud' }}</div>
                    <div class="md:col-span-2"><span class="block text-xs font-semibold uppercase text-gray-500">Vialidad</span>{{ $registro->vialidad ?? 'Sin vialidad' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Unidad de transporte</span>{{ $registro->unidad ?? 'Sin unidad' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Número económico</span>{{ $registro->numero ?? 'Sin número' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Placa</span>{{ $registro->placa ?? 'Sin placa' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Tipo de publicidad</span>{{ $registro->tipo_publicidad_nombre ?? 'Sin tipo' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Medidas</span>{{ $registro->publicacion_medidas ?? 'Sin medidas' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Versión</span>{{ $registro->publicacion_version ?? 'Sin versión' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Referencia</span>{{ $registro->referencia ?? 'Sin referencia' }}</div>
                    <div class="md:col-span-2"><span class="block text-xs font-semibold uppercase text-gray-500">Referencia domiciliaria</span>{{ $registro->referencia_domiciliaria ?? 'Sin referencia domiciliaria' }}</div>
                    <div class="md:col-span-3"><span class="block text-xs font-semibold uppercase text-gray-500">Observaciones</span>{{ $registro->observaciones ?? 'Sin observaciones' }}</div>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-md">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Datos cualitativos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Valoración</span>{{ $registro->cuali_valoracion ?? 'Sin valoración' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Lenguaje inclusivo</span>{{ $registro->cuali_lenguaje_inclusivo ?? 'Sin dato' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Estereotipo</span>{{ $registro->cuali_estereotipo ?? 'Sin estereotipo' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Tema de violencia</span>{{ $registro->violencia_tema_nombre ?? 'Sin tema' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Objetividad</span>{{ $registro->cuali_objetividad ?? 'Sin dato' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Equidad</span>{{ $registro->cuali_equidad ?? 'Sin dato' }}</div>
                    <div><span class="block text-xs font-semibold uppercase text-gray-500">Calidad</span>{{ $registro->cuali_calidad ?? 'Sin dato' }}</div>
                </div>
            </div>

            <div class="rounded-xl bg-white p-6 shadow-md">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Imágenes</h3>
                @php($archivos = is_array($registro->archivos) ? $registro->archivos : [])
                @if (count($archivos) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($archivos as $archivo)
                            <a href="{{ asset('storage/' . $archivo) }}" target="_blank" class="block overflow-hidden rounded-lg border border-gray-200 bg-gray-50">
                                <img src="{{ asset('storage/' . $archivo) }}" alt="Imagen de propaganda móvil" class="h-48 w-full object-cover">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">Este registro no tiene imágenes cargadas.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registro de medio impreso #{{ $registro->id }}
            </h2>

            <a href="{{ route('m-impresos-index') }}"
               class="inline-flex items-center rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                Volver
            </a>
        </div>
    </x-slot>

    @php
        $archivos = [];

        if (is_array($registro->archivos)) {
            $archivos = $registro->archivos;
        } elseif (is_string($registro->archivos) && $registro->archivos !== '') {
            $archivos = json_decode($registro->archivos, true) ?: [];
        }
    @endphp

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="bg-white rounded-2xl shadow p-6 space-y-6">

                <div class="border-b pb-4">
                    <h3 class="text-lg font-bold text-gray-800">
                        Información general
                    </h3>
                    <p class="text-sm text-gray-500">
                        Detalle completo del registro impreso capturado.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">ID</p>
                        <p class="text-gray-800 font-medium">{{ $registro->id }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Fecha de publicación</p>
                        <p class="text-gray-800 font-medium">
                            {{ $registro->publicacion_fecha ? \Carbon\Carbon::parse($registro->publicacion_fecha)->format('d/m/Y') : 'Sin fecha' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Fecha de registro</p>
                        <p class="text-gray-800 font-medium">
                            {{ $registro->created_at ? \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y H:i') : 'Sin fecha' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Sujeto</p>
                        <p class="text-gray-800 font-medium">{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Organización política</p>
                        <p class="text-gray-800 font-medium">{{ $registro->organizacion_nombre ?? 'Sin organización' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Periodo</p>
                        <p class="text-gray-800 font-medium">{{ $registro->periodo_nombre ?? 'Sin periodo' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Etapa del sujeto</p>
                        <p class="text-gray-800 font-medium">
                            {{ $registro->etapa_sujeto ? str_replace('_', ' ', ucfirst($registro->etapa_sujeto)) : 'Sin etapa' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Tipo de elección</p>
                        <p class="text-gray-800 font-medium">{{ $registro->tipo_eleccion_nombre ?? 'Sin tipo de elección' }}</p>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase">Medio impreso</p>
                        <p class="text-gray-800 font-medium">{{ $registro->medio_prensa_nombre ?? 'Sin medio impreso' }}</p>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Datos de la publicación
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Lugar de publicación</p>
                            <p class="text-gray-800 font-medium">{{ $registro->publicacion_lugar ?: 'Sin lugar' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Tamaño de publicación</p>
                            <p class="text-gray-800 font-medium">{{ $registro->tamano_nombre ?? 'Sin tamaño' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Género periodístico</p>
                            <p class="text-gray-800 font-medium">{{ $registro->genero_nombre ?? 'Sin género' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Sección</p>
                            <p class="text-gray-800 font-medium">{{ $registro->publicacion_seccion ?: 'Sin sección' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Página</p>
                            <p class="text-gray-800 font-medium">{{ $registro->publicacion_pagina ?: 'Sin página' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Género del autor</p>
                            <p class="text-gray-800 font-medium">{{ $registro->genero_autor_nombre ?? 'Sin género' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Nombre del autor</p>
                            <p class="text-gray-800 font-medium">{{ $registro->nombre_autor ?: 'Sin autor' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Referencia</p>
                            <p class="text-gray-800 font-medium">{{ $registro->referencia ?: 'Sin referencia' }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Observaciones</p>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">
                        {{ $registro->observaciones ?: 'Sin observaciones' }}
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Análisis cualitativo
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Valoración</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_valoracion ?: 'Sin valoración' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Lenguaje inclusivo</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_lenguaje_inclusivo ?: 'Sin dato' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Estereotipo</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_estereotipo ?: 'Sin dato' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Tema de violencia</p>
                            <p class="text-gray-800 font-medium">{{ $registro->violencia_tema_nombre ?? 'Sin tema' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Tipo de elección cualitativo</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_tipo_eleccion_nombre ?? 'Sin tipo' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Criterio de evaluación</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_criterio_evaluacion ?: 'Sin criterio' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Modalidad</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_modalidad ?: 'Sin modalidad' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Periodicidad</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_periodicidad ?: 'Sin periodicidad' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Tiraje</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_tiraje ?? 'Sin tiraje' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Circulación</p>
                            <p class="text-gray-800 font-medium">{{ $registro->cuali_circulacion ?: 'Sin circulación' }}</p>
                        </div>

                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Distrito</p>
                            <p class="text-gray-800 font-medium">{{ $registro->distrito_nombre ?? 'Sin distrito' }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Resumen</p>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">
                            {{ $registro->cuali_resumen ?: 'Sin resumen' }}
                        </div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        Archivos del registro
                    </h3>

                    @if (count($archivos) > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($archivos as $archivo)
                                <a href="{{ asset('storage/' . $archivo) }}"
                                   target="_blank"
                                   class="block rounded-xl overflow-hidden border border-gray-200 bg-gray-50 hover:shadow">
                                    <img src="{{ asset('storage/' . $archivo) }}"
                                         alt="Archivo del registro {{ $registro->id }}"
                                         class="w-full h-64 object-contain bg-gray-100">

                                    <div class="p-3 text-sm text-gray-600 truncate">
                                        {{ basename($archivo) }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-500">
                            Este registro no tiene archivos cargados.
                        </div>
                    @endif
                </div>

                <div class="border-t pt-6 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('m-impresos-testigo', $registro->id) }}"
                       target="_blank"
                       class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Ver testigo PDF
                    </a>

                    <a href="{{ route('m-impresos-index') }}"
                       class="inline-flex justify-center rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                        Ir a medios impresos
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registro de radio #{{ $registro->id }}
            </h2>

            <a href="{{ route('m-radio-index') }}" class="inline-flex items-center rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
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
                    <h3 class="text-lg font-bold text-gray-800">Información general</h3>
                    <p class="text-sm text-gray-500">Detalle completo del registro de radio capturado.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">ID</p><p class="text-gray-800 font-medium">{{ $registro->id }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Fecha de publicación</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_fecha ? \Carbon\Carbon::parse($registro->publicacion_fecha)->format('d/m/Y') : 'Sin fecha' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Fecha de registro</p><p class="text-gray-800 font-medium">{{ $registro->created_at ? \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y H:i') : 'Sin fecha' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Sujeto</p><p class="text-gray-800 font-medium">{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Organización política</p><p class="text-gray-800 font-medium">{{ $registro->organizacion_nombre ?? 'Sin organización' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Periodo</p><p class="text-gray-800 font-medium">{{ $registro->periodo_nombre ?? 'Sin periodo' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Etapa del sujeto</p><p class="text-gray-800 font-medium">{{ $registro->etapa_sujeto ? str_replace('_', ' ', ucfirst($registro->etapa_sujeto)) : 'Sin etapa' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Tipo de elección</p><p class="text-gray-800 font-medium">{{ $registro->tipo_eleccion_nombre ?? 'Sin tipo de elección' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Género del autor</p><p class="text-gray-800 font-medium">{{ $registro->genero_autor_nombre ?? 'Sin género' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Nombre del autor</p><p class="text-gray-800 font-medium">{{ $registro->nombre_autor ?: 'Sin autor' }}</p></div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Datos del medio</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Nombre</p><p class="text-gray-800 font-medium">{{ $registro->medio_nombre ?: 'Sin medio' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Siglas</p><p class="text-gray-800 font-medium">{{ $registro->medio_siglas ?: 'Sin siglas' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Banda</p><p class="text-gray-800 font-medium">{{ $registro->medio_banda ?: 'Sin banda' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Grupo radiofónico</p><p class="text-gray-800 font-medium">{{ $registro->medio_grupo_radiofonico ?: 'Sin grupo' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Municipio</p><p class="text-gray-800 font-medium">{{ $registro->municipio_nombre ?? 'Sin municipio' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Cobertura</p><p class="text-gray-800 font-medium">{{ $registro->medio_cobertura ?: 'Sin cobertura' }}</p></div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Datos de la publicación</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Hora</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_hora ? \Carbon\Carbon::parse($registro->publicacion_hora)->format('H:i') : 'Sin hora' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Tiempo</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_tiempo ? $registro->publicacion_tiempo . ' segundos' : 'Sin tiempo' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Tipo</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_tipo ?: 'Sin tipo' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Ubicación</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_ubicacion ?: 'Sin ubicación' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Modalidad</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_modalidad ?: 'Sin modalidad' }}</p></div>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Observaciones</p>
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">{{ $registro->observaciones ?: 'Sin observaciones' }}</div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Análisis cualitativo</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Valoración</p><p class="text-gray-800 font-medium">{{ $registro->cuali_valoracion ?: 'Sin valoración' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Lenguaje inclusivo</p><p class="text-gray-800 font-medium">{{ $registro->cuali_lenguaje_inclusivo ?: 'Sin dato' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Estereotipo</p><p class="text-gray-800 font-medium">{{ $registro->cuali_estereotipo ?: 'Sin dato' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Tema de violencia</p><p class="text-gray-800 font-medium">{{ $registro->violencia_tema_nombre ?? 'Sin tema' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Candidatura</p><p class="text-gray-800 font-medium">{{ $registro->cuali_tipo_eleccion_nombre ?? 'Sin candidatura' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Criterio de evaluación</p><p class="text-gray-800 font-medium">{{ $registro->cuali_criterio_evaluacion ?: 'Sin criterio' }}</p></div>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Resumen</p>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">{{ $registro->cuali_resumen ?: 'Sin resumen' }}</div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Audios del registro</h3>
                    @if (count($archivos) > 0)
                        <div class="space-y-4">
                            @foreach ($archivos as $archivo)
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="mb-2 flex items-center justify-between gap-3">
                                        <p class="truncate text-sm font-medium text-gray-700">{{ basename($archivo) }}</p>
                                        <a href="{{ asset('storage/' . $archivo) }}" target="_blank" class="text-sm font-medium text-primary-700 hover:underline">Abrir</a>
                                    </div>
                                    <audio controls preload="metadata" class="w-full">
                                        <source src="{{ asset('storage/' . $archivo) }}" type="audio/mpeg">
                                        Tu navegador no soporta el reproductor de audio.
                                    </audio>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-500">Este registro no tiene audios cargados.</div>
                    @endif
                </div>

                <div class="border-t pt-6 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('m-radio-testigo', $registro->id) }}" target="_blank" class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Ver testigo PDF
                    </a>
                    <a href="{{ route('m-radio-index') }}" class="inline-flex justify-center rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                        Ir a radio
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

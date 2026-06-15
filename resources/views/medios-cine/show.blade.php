<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registro de cine #{{ $registro->id }}
            </h2>
            <a href="{{ route('m-cine-index') }}" class="rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Regresar</a>
        </div>
    </x-slot>

    @php $archivos = is_array($registro->archivos) ? $registro->archivos : []; @endphp

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6 rounded-xl bg-white p-6 shadow-md">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Datos principales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Organización</p><p class="text-gray-800 font-medium">{{ $registro->organizacion_nombre ?? 'Sin organización' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Sujeto</p><p class="text-gray-800 font-medium">{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Tipo de elección</p><p class="text-gray-800 font-medium">{{ $registro->tipo_eleccion_nombre ?? 'Sin tipo' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Cine</p><p class="text-gray-800 font-medium">{{ $registro->cine_nombre_comercial ?: ($registro->cine_nombre ?? 'Sin cine') }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Distrito</p><p class="text-gray-800 font-medium">{{ $registro->distrito_nombre ?? 'Sin distrito' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Municipio / Localidad</p><p class="text-gray-800 font-medium">{{ $registro->municipio_nombre ?? 'Sin municipio' }}{{ $registro->localidad_nombre ? ' / ' . $registro->localidad_nombre : '' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Sala</p><p class="text-gray-800 font-medium">{{ $registro->medio_sala ?: 'Sin sala' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Fecha</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_fecha ? $registro->publicacion_fecha->format('d/m/Y') : 'Sin fecha' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Hora</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_hora ? \Carbon\Carbon::parse($registro->publicacion_hora)->format('H:i') : 'Sin hora' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Tiempo</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_tiempo ? $registro->publicacion_tiempo . 's' : 'Sin tiempo' }}</p></div>
                        <div class="md:col-span-2"><p class="text-xs font-semibold text-gray-500 uppercase">Referencia</p><p class="text-gray-800 font-medium">{{ $registro->referencia ?: 'Sin referencia' }}</p></div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Datos cualitativos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Valoración</p><p class="text-gray-800 font-medium">{{ $registro->cuali_valoracion ?: 'Sin dato' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Lenguaje inclusivo</p><p class="text-gray-800 font-medium">{{ $registro->cuali_lenguaje_inclusivo ?: 'Sin dato' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Estereotipo</p><p class="text-gray-800 font-medium">{{ $registro->cuali_estereotipo ?: 'Sin dato' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Tema de violencia</p><p class="text-gray-800 font-medium">{{ $registro->violencia_tema_nombre ?? 'Sin tema' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Criterio de evaluación</p><p class="text-gray-800 font-medium">{{ $registro->cuali_criterio_evaluacion ?: 'Sin criterio' }}</p></div>
                    </div>
                    <div class="mt-4"><p class="text-xs font-semibold text-gray-500 uppercase mb-1">Resumen</p><div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">{{ $registro->cuali_resumen ?: 'Sin resumen' }}</div></div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Archivos del registro</h3>
                    @if (count($archivos) > 0)
                        <div class="space-y-4">
                            @foreach ($archivos as $archivo)
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="mb-2 flex items-center justify-between gap-3">
                                        <p class="truncate text-sm font-medium text-gray-700">{{ basename($archivo) }}</p>
                                        <a href="{{ asset('storage/' . $archivo) }}" target="_blank" class="text-sm font-medium text-primary-700 hover:underline">Abrir</a>
                                    </div>
                                    <video controls preload="metadata" class="w-full rounded-lg"><source src="{{ asset('storage/' . $archivo) }}">Tu navegador no soporta el reproductor de video.</video>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-500">Este registro no tiene archivos cargados.</div>
                    @endif
                </div>

                <div class="border-t pt-6 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('m-cine-testigo', $registro->id) }}" target="_blank" class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">Ver testigo PDF</a>
                    <a href="{{ route('m-cine-index') }}" class="inline-flex justify-center rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Ir a cine</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

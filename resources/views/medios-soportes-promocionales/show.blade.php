<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Registro de soporte promocional #{{ $registro->id }}
            </h2>

            <a href="{{ route('m-soportes-promocionales-index') }}" class="inline-flex items-center rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
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
                    <p class="text-sm text-gray-500">Detalle completo del soporte promocional capturado.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">ID</p><p class="text-gray-800 font-medium">{{ $registro->id }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Fecha de registro</p><p class="text-gray-800 font-medium">{{ $registro->created_at ? \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y H:i') : 'Sin fecha' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Sujeto</p><p class="text-gray-800 font-medium">{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Organización política</p><p class="text-gray-800 font-medium">{{ $registro->organizacion_nombre ?? 'Sin organización' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Periodo</p><p class="text-gray-800 font-medium">{{ $registro->periodo_nombre ?? 'Sin periodo' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Etapa del sujeto</p><p class="text-gray-800 font-medium">{{ $registro->etapa_sujeto ? str_replace('_', ' ', ucfirst($registro->etapa_sujeto)) : 'Sin etapa' }}</p></div>
                    <div><p class="text-xs font-semibold text-gray-500 uppercase">Candidatura</p><p class="text-gray-800 font-medium">{{ $registro->tipo_eleccion_nombre ?? 'Sin candidatura' }}</p></div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Ubicación</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Distrito</p><p class="text-gray-800 font-medium">{{ $registro->distrito_nombre ?? 'Sin distrito' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Municipio</p><p class="text-gray-800 font-medium">{{ $registro->municipio_nombre ?? 'Sin municipio' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Localidad</p><p class="text-gray-800 font-medium">{{ $registro->localidad_nombre ?? 'Sin localidad' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Sección</p><p class="text-gray-800 font-medium">{{ $registro->seccion ?: 'Sin sección' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Latitud</p><p class="text-gray-800 font-medium">{{ $registro->latitud ?: 'Sin latitud' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Longitud</p><p class="text-gray-800 font-medium">{{ $registro->longitud ?: 'Sin longitud' }}</p></div>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Vialidad</p>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">{{ $registro->vialidad ?: 'Sin vialidad' }}</div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Datos del soporte</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Tipo de publicidad</p><p class="text-gray-800 font-medium">{{ $registro->tipo_publicidad_nombre ?? 'Sin tipo' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Medidas</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_medidas ?: 'Sin medidas' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Versión</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_version ?: 'Sin versión' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Cantidad</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_cantidad ?? 'Sin cantidad' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Número de fotos</p><p class="text-gray-800 font-medium">{{ $registro->publicacion_numero_fotos ?? 'Sin número' }}</p></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Referencia</p>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">{{ $registro->referencia ?: 'Sin referencia' }}</div>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase mb-1">Referencia domiciliaria</p>
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-700">{{ $registro->referencia_domiciliaria ?: 'Sin referencia domiciliaria' }}</div>
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
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Objetividad</p><p class="text-gray-800 font-medium">{{ $registro->cuali_objetividad ?: 'Sin dato' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Equidad</p><p class="text-gray-800 font-medium">{{ $registro->cuali_equidad ?: 'Sin dato' }}</p></div>
                        <div><p class="text-xs font-semibold text-gray-500 uppercase">Calidad</p><p class="text-gray-800 font-medium">{{ $registro->cuali_calidad ?: 'Sin dato' }}</p></div>
                    </div>
                </div>

                <div class="border-t pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Imágenes del registro</h3>
                    @if (count($archivos) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach ($archivos as $archivo)
                                <a href="{{ asset('storage/' . $archivo) }}" target="_blank" class="overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                                    <img src="{{ asset('storage/' . $archivo) }}" alt="Imagen del soporte promocional" class="h-56 w-full object-cover">
                                    <div class="p-2 text-xs text-primary-700 truncate">{{ basename($archivo) }}</div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-gray-500">Este registro no tiene imágenes cargadas.</div>
                    @endif
                </div>

                <div class="border-t pt-6 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('m-soportes-promocionales-testigo', $registro->id) }}" target="_blank" class="inline-flex justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700">
                        Ver testigo PDF
                    </a>
                    <a href="{{ route('m-soportes-promocionales-index') }}" class="inline-flex justify-center rounded-lg bg-gray-700 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                        Ir a soportes promocionales
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

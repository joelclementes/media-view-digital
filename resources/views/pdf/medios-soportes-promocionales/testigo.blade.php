<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Testigo soportes promocionales</title>
    <style>
        @page { margin: 22px 28px; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111; }
        .header-table { width: 100%; border-collapse: collapse; }
        .logo-box { width: 150px; height: 70px; border: 1px dashed #999; text-align: center; vertical-align: middle; font-size: 9px; color: #777; }
        .header-text { text-align: right; font-size: 11px; line-height: 1.25; font-weight: bold; }
        .title { text-align: center; font-size: 12px; font-weight: bold; margin: 10px 0 8px; }
        .box { border: 1px solid #111; padding: 7px; box-sizing: border-box; margin-top: 8px; }
        .label { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .grid { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .col { width: 50%; vertical-align: top; }
        .image-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        .image-cell { width: 50%; border: 1px solid #111; padding: 5px; text-align: center; vertical-align: middle; height: 190px; }
        .image-cell img { max-width: 100%; max-height: 180px; }
        p { margin: 0 0 6px 0; padding: 0; }
    </style>
</head>
<body>
    @php
        $horaLevantamiento = $registro->created_at ? $registro->created_at->format('Y-m-d H:i:s') : '';
    @endphp

    <table class="header-table">
        <tr>
            <td class="logo-box">ESPACIO PARA<br>ENCABEZADO / LOGO</td>
            <td class="header-text">
                ORGANISMO PÚBLICO LOCAL ELECTORAL DE VERACRUZ<br>
                PROGRAMA DE MONITOREO A MEDIOS DE COMUNICACIÓN<br>
                TIPO: SOPORTES PROMOCIONALES<br>
                REPORTE DE LA SEMANA DEL ____ AL ____ DEL ____
            </td>
        </tr>
    </table>

    <div class="title">TESTIGO SOPORTES PROMOCIONALES</div>

    <div class="box">
        <p><span class="label">NO.</span> {{ $registro->id }}</p>
        <p><span class="label">ACTOR POLÍTICO:</span> <span class="uppercase">{{ $registro->organizacion_nombre ?: $registro->sujeto_nombre }}</span></p>
        <p><span class="label">TIPO DE ELECCIÓN:</span> <span class="uppercase">{{ $registro->tipo_eleccion_nombre ?: 'SIN TIPO DE ELECCIÓN' }}</span></p>
        <p><span class="label">TIPO DE PUBLICIDAD:</span> <span class="uppercase">{{ $registro->tipo_publicidad_nombre ?: 'SIN TIPO' }}</span></p>
        <p><span class="label">VERSIÓN:</span> <span class="uppercase">{{ $registro->publicacion_version ?: 'SIN VERSIÓN' }}</span></p>
    </div>

    <table class="grid">
        <tr>
            <td class="col">
                <div class="box">
                    <p><span class="label">DISTRITO:</span> {{ $registro->distrito_nombre ?: 'SIN DISTRITO' }}</p>
                    <p><span class="label">MUNICIPIO:</span> {{ $registro->municipio_nombre ?: 'SIN MUNICIPIO' }}</p>
                    <p><span class="label">LOCALIDAD:</span> {{ $registro->localidad_nombre ?: 'SIN LOCALIDAD' }}</p>
                    <p><span class="label">SECCIÓN:</span> {{ $registro->seccion ?: 'SIN SECCIÓN' }}</p>
                    <p><span class="label">HORA DE LEVANTAMIENTO:</span> {{ $horaLevantamiento }}</p>
                </div>
            </td>
            <td class="col">
                <div class="box">
                    <p><span class="label">MEDIDAS:</span> <span class="uppercase">{{ $registro->publicacion_medidas ?: 'SIN MEDIDAS' }}</span></p>
                    <p><span class="label">CANTIDAD:</span> {{ $registro->publicacion_cantidad ?? 'SIN CANTIDAD' }}</p>
                    <p><span class="label">NO. FOTOS:</span> {{ $registro->publicacion_numero_fotos ?? 'SIN DATO' }}</p>
                    <p><span class="label">LATITUD:</span> {{ $registro->latitud ?: 'SIN LATITUD' }}</p>
                    <p><span class="label">LONGITUD:</span> {{ $registro->longitud ?: 'SIN LONGITUD' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <div class="box">
        <p><span class="label">VIALIDAD:</span></p>
        <p class="uppercase">{{ $registro->vialidad ?: '' }}</p>
    </div>

    <div class="box">
        <p><span class="label">REFERENCIA:</span></p>
        <p class="uppercase">{{ $registro->referencia ?: '' }}</p>
        <p><span class="label">REFERENCIA DOMICILIARIA:</span></p>
        <p class="uppercase">{{ $registro->referencia_domiciliaria ?: '' }}</p>
    </div>

    <div class="box">
        <p><span class="label">OBSERVACIONES:</span></p>
        <p class="uppercase">{{ $registro->observaciones ?: '' }}</p>
    </div>

    <table class="image-table">
        @forelse (array_chunk($rutas_imagenes, 2) as $fila)
            <tr>
                @foreach ($fila as $imagen)
                    <td class="image-cell"><img src="{{ $imagen }}" alt="Imagen soporte"></td>
                @endforeach
                @if (count($fila) === 1)
                    <td class="image-cell">SIN IMAGEN</td>
                @endif
            </tr>
        @empty
            <tr><td class="image-cell" colspan="2">SIN IMÁGENES DISPONIBLES</td></tr>
        @endforelse
    </table>
</body>
</html>

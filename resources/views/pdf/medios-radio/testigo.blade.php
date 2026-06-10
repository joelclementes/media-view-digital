<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Testigo radio</title>
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
        p { margin: 0 0 6px 0; padding: 0; }
    </style>
</head>
<body>
    @php
        $meses = ['Jan'=>'ENE','Feb'=>'FEB','Mar'=>'MAR','Apr'=>'ABR','May'=>'MAY','Jun'=>'JUN','Jul'=>'JUL','Aug'=>'AGO','Sep'=>'SEP','Oct'=>'OCT','Nov'=>'NOV','Dec'=>'DIC'];
        $fecha = $registro->publicacion_fecha
            ? $registro->publicacion_fecha->format('d') . '-' . ($meses[$registro->publicacion_fecha->format('M')] ?? strtoupper($registro->publicacion_fecha->format('M'))) . '-' . $registro->publicacion_fecha->format('Y')
            : '';
        $horaPublicacion = $registro->publicacion_hora ? \Carbon\Carbon::parse($registro->publicacion_hora)->format('H:i') : '';
        $horaLevantamiento = $registro->created_at ? $registro->created_at->format('Y-m-d H:i:s') : '';
        $archivos = is_array($registro->archivos) ? $registro->archivos : [];
    @endphp

    <table class="header-table">
        <tr>
            <td class="logo-box">ESPACIO PARA<br>ENCABEZADO / LOGO</td>
            <td class="header-text">
                ORGANISMO PÚBLICO LOCAL ELECTORAL DE VERACRUZ<br>
                PROGRAMA DE MONITOREO A MEDIOS DE COMUNICACIÓN<br>
                TIPO: RADIO<br>
                REPORTE DE LA SEMANA DEL ____ AL ____ DEL ____
            </td>
        </tr>
    </table>

    <div class="title">TESTIGO RADIO</div>

    <div class="box">
        <p><span class="label">NO.</span> {{ $registro->id }}</p>
        <p><span class="label">ACTOR POLÍTICO:</span> <span class="uppercase">{{ $registro->organizacion_nombre ?: $registro->sujeto_nombre }}</span></p>
        <p><span class="label">TIPO DE ELECCIÓN:</span> <span class="uppercase">{{ $registro->tipo_eleccion_nombre ?: 'SIN TIPO DE ELECCIÓN' }}</span></p>
        <p><span class="label">MEDIO RADIO:</span> <span class="uppercase">{{ $registro->medio_nombre ?: 'SIN MEDIO' }}</span></p>
        <p><span class="label">SIGLAS / BANDA:</span> <span class="uppercase">{{ trim(($registro->medio_siglas ?: '') . ' ' . ($registro->medio_banda ?: '')) ?: 'SIN DATO' }}</span></p>
        <p><span class="label">GRUPO RADIOFÓNICO:</span> <span class="uppercase">{{ $registro->medio_grupo_radiofonico ?: 'SIN GRUPO' }}</span></p>
        <p><span class="label">MUNICIPIO / COBERTURA:</span> <span class="uppercase">{{ ($registro->municipio_nombre ?: 'SIN MUNICIPIO') . ' / ' . ($registro->medio_cobertura ?: 'SIN COBERTURA') }}</span></p>
    </div>

    <table class="grid">
        <tr>
            <td class="col">
                <div class="box">
                    <p><span class="label">FECHA:</span> {{ $fecha }}</p>
                    <p><span class="label">HORA:</span> {{ $horaPublicacion }}</p>
                    <p><span class="label">TIEMPO:</span> {{ $registro->publicacion_tiempo ? $registro->publicacion_tiempo . ' SEGUNDOS' : 'SIN TIEMPO' }}</p>
                    <p><span class="label">HORA DE LEVANTAMIENTO:</span> {{ $horaLevantamiento }}</p>
                </div>
            </td>
            <td class="col">
                <div class="box">
                    <p><span class="label">TIPO:</span> <span class="uppercase">{{ $registro->publicacion_tipo ?: 'SIN TIPO' }}</span></p>
                    <p><span class="label">UBICACIÓN:</span> <span class="uppercase">{{ $registro->publicacion_ubicacion ?: 'SIN UBICACIÓN' }}</span></p>
                    <p><span class="label">MODALIDAD:</span> <span class="uppercase">{{ $registro->publicacion_modalidad ?: 'SIN MODALIDAD' }}</span></p>
                    <p><span class="label">GÉNERO DEL AUTOR:</span> <span class="uppercase">{{ $registro->genero_autor_nombre ?: 'SIN GÉNERO' }}</span></p>
                </div>
            </td>
        </tr>
    </table>

    <div class="box">
        <p><span class="label">RESUMEN:</span></p>
        <p class="uppercase">{{ $registro->cuali_resumen ?: $registro->observaciones }}</p>
    </div>

    <div class="box">
        <p><span class="label">ARCHIVOS DE AUDIO:</span></p>
        @forelse ($archivos as $archivo)
            <p class="uppercase">{{ basename($archivo) }}</p>
        @empty
            <p>SIN AUDIO DISPONIBLE</p>
        @endforelse
    </div>

    <div class="box">
        <p><span class="label">OBSERVACIONES:</span></p>
        <p class="uppercase">{{ $registro->observaciones ?: '' }}</p>
    </div>
</body>
</html>

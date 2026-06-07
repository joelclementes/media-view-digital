<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Testigo</title>

    <style>
        @page {
            margin: 20px 26px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
        }

        .header {
            width: 100%;
            margin-bottom: 8px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-box {
            width: 150px;
            height: 70px;
            border: 1px dashed #999;
            text-align: center;
            vertical-align: middle;
            font-size: 9px;
            color: #777;
        }

        .header-text {
            text-align: right;
            font-size: 11px;
            line-height: 1.25;
            font-weight: bold;
        }

        .title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin: 6px 0 4px;
        }

        .box {
            border: 1px solid #111;
            padding: 6px;
            box-sizing: border-box;
        }

        .image-box {
            height: 310px;
            padding: 6px;
            overflow: hidden;
            text-align: center;
            font-size: 0;
        }

        .image-table {
            width: 100%;
            height: 298px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .image-cell-one {
            width: 100%;
            height: 298px;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
        }

        .image-cell-two {
            width: 50%;
            height: 298px;
            text-align: center;
            vertical-align: middle;
            overflow: hidden;
        }

        .testigo-image {
            max-width: 100%;
            max-height: 298px;
            width: auto;
            height: auto;
            display: inline-block;
            vertical-align: middle;
        }

        .text-box {
            height: 120px;
            margin-top: 8px;
            line-height: 1.35;
        }

        .row-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 8px;
        }

        .left-col {
            width: 40%;
            padding-right: 8px;
            vertical-align: top;
        }

        .right-col {
            width: 60%;
            vertical-align: top;
        }

        .info-box-left {
            height: 145px;
            line-height: 1.35;
        }

        .genre-box {
            height: 76px;
            margin-top: 8px;
            line-height: 1.35;
        }

        .info-box-right {
            height: 229px;
            line-height: 1.6;
        }

        .observaciones-box {
            height: 120px;
            margin-top: 8px;
            line-height: 1.35;
        }

        .label {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .mt {
            margin-top: 12px;
        }

        table {
            width: 100%;
        }

        p {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>
    @php
        $meses = [
            'Jan' => 'ENE',
            'Feb' => 'FEB',
            'Mar' => 'MAR',
            'Apr' => 'ABR',
            'May' => 'MAY',
            'Jun' => 'JUN',
            'Jul' => 'JUL',
            'Aug' => 'AGO',
            'Sep' => 'SEP',
            'Oct' => 'OCT',
            'Nov' => 'NOV',
            'Dec' => 'DIC',
        ];

        $fecha = $registro->publicacion_fecha
            ? $registro->publicacion_fecha->format('d') .
                '-' .
                ($meses[$registro->publicacion_fecha->format('M')] ?? strtoupper($registro->publicacion_fecha->format('M'))) .
                '-' .
                $registro->publicacion_fecha->format('Y')
            : '';

        $horaLevantamiento = $registro->created_at ? $registro->created_at->format('Y-m-d H:i:s') : '';

        $referencia = $registro->referencia ?: '';
        $resumen = $registro->cuali_resumen ?: $registro->observaciones;
        $observaciones = $registro->observaciones ?: '';
    @endphp

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="logo-box">
                    ESPACIO PARA<br>
                    ENCABEZADO / LOGO
                </td>

                <td class="header-text">
                    ORGANISMO PÚBLICO LOCAL ELECTORAL DE VERACRUZ<br>
                    PROGRAMA DE MONITOREO A MEDIOS DE COMUNICACIÓN<br>
                    TIPO: MEDIOS IMPRESOS<br>
                    REPORTE DE LA SEMANA DEL ____ AL ____ DEL ____
                </td>
            </tr>
        </table>
    </div>

    <div class="title">TESTIGO</div>

    <div class="box image-box">
        @if (!empty($rutas_imagenes))
            <table class="image-table">
                <tr>
                    @if (count($rutas_imagenes) === 1)
                        <td class="image-cell-one">
                            <img src="{{ $rutas_imagenes[0] }}" class="testigo-image">
                        </td>
                    @else
                        @foreach ($rutas_imagenes as $ruta_imagen)
                            <td class="image-cell-two">
                                <img src="{{ $ruta_imagen }}" class="testigo-image">
                            </td>
                        @endforeach
                    @endif
                </tr>
            </table>
        @else
            <div style="padding-top: 130px; color: #777; font-size: 10px;">
                SIN IMAGEN DISPONIBLE
            </div>
        @endif
    </div>

    <div class="box text-box">
        <p>
            <span class="label">REFERENCIA:</span>
            <span class="uppercase">{{ $referencia }}</span>
        </p>

        <p class="mt">
            <span class="label">RESUMEN:</span>
            <span class="uppercase">{{ $resumen }}</span>
        </p>
    </div>

    <table class="row-table">
        <tr>
            <td class="left-col">
                <div class="box info-box-left">
                    <p>
                        <span class="label">NO.</span>
                        {{ $registro->id }}
                    </p>

                    <p class="mt">
                        <span class="label">ACTOR POLÍTICO:</span><br>
                        <span class="uppercase">
                            {{ $registro->organizacion_nombre ?: $registro->sujeto_nombre }}
                        </span>
                    </p>

                    <p class="mt">
                        <span class="label">TIPO DE ELECCIÓN.</span>
                        <span class="uppercase">
                            {{ $registro->tipo_eleccion_nombre ?: 'SIN TIPO DE ELECCIÓN' }}
                        </span>
                    </p>
                </div>

                <div class="box genre-box">
                    <p>
                        <span class="label">GÉNERO:</span><br>
                        <span class="uppercase">
                            {{ $registro->genero_nombre ?: 'SIN GÉNERO' }}
                        </span>
                    </p>
                </div>
            </td>

            <td class="right-col">
                <div class="box info-box-right">
                    <p>
                        <span class="label">MEDIO IMPRESO:</span><br>
                        <span class="uppercase">
                            {{ $registro->medio_prensa_nombre ?: 'SIN MEDIO IMPRESO' }}
                        </span>
                    </p>

                    <p class="mt">
                        <span class="label">FECHA:</span>
                        {{ $fecha }}
                    </p>

                    <p class="mt">
                        <span class="label">HORA DE LEVANTAMIENTO:</span>
                        {{ $horaLevantamiento }}
                    </p>

                    <p class="mt">
                        <span class="label">TAMAÑO:</span>
                        <span class="uppercase">
                            {{ $registro->tamano_nombre ?: 'SIN TAMAÑO' }}
                        </span>
                    </p>

                    <p class="mt">
                        <span class="label">SECCIÓN:</span>
                        <span class="uppercase">
                            {{ $registro->publicacion_seccion ?: 'SIN SECCIÓN' }}
                        </span>
                    </p>

                    <p class="mt">
                        <span class="label">PÁGINA:</span>
                        <span class="uppercase">
                            {{ $registro->publicacion_pagina ?: 'SIN PÁGINA' }}
                        </span>
                    </p>
                </div>
            </td>
        </tr>
    </table>

    <div class="box observaciones-box">
        <span class="label">OBSERVACIONES.</span>
        <span class="uppercase">{{ $observaciones }}</span>
    </div>
</body>

</html>
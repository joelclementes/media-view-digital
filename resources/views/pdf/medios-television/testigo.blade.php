<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Testigo televisión</title>

    <style>
        @page {
            margin: 28px 36px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .logo-cell {
            width: 180px;
            vertical-align: top;
        }

        .logo-placeholder {
            width: 145px;
            height: 78px;
            border: 0;
            font-size: 9px;
            color: #777;
            text-align: center;
            line-height: 78px;
        }

        .header-text {
            text-align: center;
            font-family: DejaVu Serif, serif;
            font-size: 13px;
            font-weight: bold;
            line-height: 1.2;
            vertical-align: top;
            padding-top: 18px;
        }

        .title {
            text-align: center;
            font-family: DejaVu Serif, serif;
            font-size: 14px;
            font-weight: bold;
            margin: 8px 0 -1px;
        }

        .box {
            border: 1px solid #333;
            padding: 6px;
            box-sizing: border-box;
        }

        .top-box {
            width: 100%;
            min-height: 72px;
            margin-bottom: 10px;
        }

        .layout-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .left-col {
            width: 40%;
            vertical-align: top;
            padding-right: 10px;
        }

        .right-col {
            width: 60%;
            vertical-align: top;
        }

        .info-box {
            min-height: 175px;
            margin-bottom: 10px;
        }

        .small-box {
            min-height: 78px;
        }

        .summary-box {
            min-height: 255px;
        }

        p {
            margin: 0 0 6px 0;
            padding: 0;
            line-height: 1.15;
        }

        .label {
            font-weight: normal;
        }

        .value {
            text-transform: uppercase;
        }

        .mt-lg {
            margin-top: 52px;
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
            ? $registro->publicacion_fecha->format('d') . '-' . ($meses[$registro->publicacion_fecha->format('M')] ?? strtoupper($registro->publicacion_fecha->format('M'))) . '-' . $registro->publicacion_fecha->format('Y')
            : '';

        $horaPublicacion = $registro->publicacion_hora
            ? \Carbon\Carbon::parse($registro->publicacion_hora)->format('H:i')
            : '';

        $horaLevantamiento = $registro->created_at
            ? $registro->created_at->format('Y-m-d H:i:s')
            : '';

        $archivos = is_array($registro->archivos) ? $registro->archivos : [];

        $tipoSenal = $registro->medio_tipo_senal ?: '';

        $plaza = $registro->plaza_nombre ?: $registro->municipio_nombre;

        $actorPolitico = $registro->organizacion_nombre ?: $registro->sujeto_nombre;
    @endphp

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <div class="logo-placeholder">
                    LOGO
                </div>
            </td>

            <td class="header-text">
                ORGANISMO PÚBLICO LOCAL ELECTORAL DE VERACRUZ<br>
                PROGRAMA DE MONITOREO A MEDIOS DE COMUNICACIÓN<br>
                TIPO: TELEVISIÓN<br>
                REPORTE DE LA SEMANA DEL ____ AL ____ DEL ____
            </td>
        </tr>
    </table>

    <div class="title">TESTIGO</div>

    <div class="box top-box">
        <p><span class="label">No.:</span> {{ $registro->id }}</p>
        <p><span class="label">Tipo de señal:</span> <span class="value">{{ $tipoSenal ?: 'SIN DATO' }}</span></p>
        <p><span class="label">Conductor/autor:</span> <span class="value">{{ $registro->nombre_autor ?: 'SIN AUTOR' }}</span></p>
    </div>

    <table class="layout-table">
        <tr>
            <td class="left-col">
                <div class="box info-box">
                    <p><span class="label">Tipo inf.:</span> <span class="value">{{ $registro->publicacion_tipo ?: 'SIN TIPO' }}</span></p>
                    <p><span class="label">Ubicación:</span> <span class="value">{{ $registro->publicacion_ubicacion ?: 'SIN UBICACIÓN' }}</span></p>
                    <p><span class="label">Plaza:</span> <span class="value">{{ $plaza ?: 'SIN PLAZA' }}</span></p>

                    <div class="mt-lg">
                        <p>
                            <span class="label">Programa/medio:</span>
                            <span class="value">
                                {{ $registro->medio_nombre ?: 'SIN MEDIO' }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="box small-box">
                    <p><span class="label">Modalidad:</span> <span class="value">{{ $registro->publicacion_modalidad ?: 'SIN MODALIDAD' }}</span></p>
                    <p><span class="label">Tiempo transc.:</span> {{ $registro->publicacion_tiempo ? $registro->publicacion_tiempo . 's' : 'SIN TIEMPO' }}</p>
                </div>
            </td>

            <td class="right-col">
                <div class="box summary-box">
                    <p>
                        <span class="label">Organización Política:</span>
                        <span class="value">{{ $actorPolitico ?: 'SIN ORGANIZACIÓN' }}</span>
                    </p>

                    <br>

                    <p><span class="label">Candidatura:</span></p>
                    <p class="value">{{ $registro->tipo_eleccion_nombre ?: 'N/A' }}</p>

                    <br>

                    <p>
                        <span class="label">Resumen:</span>
                        <span class="value">{{ $registro->cuali_resumen ?: $registro->observaciones ?: 'SIN RESUMEN' }}</span>
                    </p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
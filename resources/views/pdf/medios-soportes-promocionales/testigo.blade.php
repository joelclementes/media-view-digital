<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Testigo soportes promocionales</title>

    <style>
        @page { margin: 24px 42px; }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #000;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }

        .logo-cell {
            width: 140px;
            vertical-align: top;
        }

        .logo-placeholder {
            width: 120px;
            height: 70px;
            text-align: center;
            font-size: 8px;
            color: #777;
            line-height: 70px;
        }

        .header-text {
            text-align: center;
            font-family: DejaVu Serif, serif;
            font-size: 10px;
            font-weight: bold;
            line-height: 1.05;
            vertical-align: top;
            padding-top: 12px;
        }

        .title {
            text-align: center;
            font-family: DejaVu Serif, serif;
            font-size: 11px;
            font-weight: bold;
            margin: 4px 0 3px;
        }

        .box {
            border: 1px solid #222;
            padding: 4px;
            box-sizing: border-box;
        }

        .image-table,
        .grid-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .image-cell {
            width: 50%;
            height: 225px;
            border: 1px solid #222;
            text-align: center;
            vertical-align: middle;
            padding: 8px;
        }

        .image-cell img {
            max-width: 100%;
            max-height: 210px;
        }

        .reference-box {
            height: 58px;
            margin-top: 6px;
        }

        .data-cell {
            width: 50%;
            height: 126px;
            vertical-align: top;
            border: 1px solid #222;
            padding: 5px;
        }

        .left-gap {
            padding-right: 4px;
        }

        .right-gap {
            padding-left: 4px;
        }

        .small-row {
            margin-top: 6px;
        }

        .small-cell {
            width: 50%;
            height: 82px;
            vertical-align: top;
            border: 1px solid #222;
            padding: 5px;
        }

        .observaciones {
            height: 82px;
            margin-top: 6px;
        }

        p {
            margin: 0 0 5px 0;
            padding: 0;
            line-height: 1.15;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .spacer {
            height: 14px;
        }
    </style>
</head>

<body>
    @php
        $horaLevantamiento = $registro->created_at ? $registro->created_at->format('Y-m-d H:i:s') : '';
        $actorPolitico = $registro->organizacion_nombre ?: $registro->sujeto_nombre;
        $imagenes = $rutas_imagenes ?? [];
        $primeraImagen = $imagenes[0] ?? null;
        $segundaImagen = $imagenes[1] ?? $primeraImagen;
    @endphp

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <div class="logo-placeholder">LOGO</div>
            </td>

            <td class="header-text">
                ORGANISMO PÚBLICO LOCAL ELECTORAL DE VERACRUZ<br>
                PROGRAMA DE MONITOREO A MEDIOS DE COMUNICACIÓN<br>
                TIPO: MEDIOS ALTERNOS<br>
                REPORTE DE LA SEMANA DEL ____ AL ____ DEL ____
            </td>
        </tr>
    </table>

    <div class="title">CÉDULA DE IDENTIFICACIÓN</div>

    <table class="image-table">
        <tr>
            <td class="image-cell">
                @if ($primeraImagen)
                    <img src="{{ $primeraImagen }}" alt="Imagen soporte 1">
                @else
                    SIN IMAGEN
                @endif
            </td>

            <td class="image-cell">
                @if ($segundaImagen)
                    <img src="{{ $segundaImagen }}" alt="Imagen soporte 2">
                @else
                    SIN IMAGEN
                @endif
            </td>
        </tr>
    </table>

    <div class="box reference-box">
        <p>
            REFERENCIA:<span class="uppercase">{{ $registro->referencia ?: 'SIN REFERENCIA' }}</span>
        </p>
    </div>

    <table class="grid-table small-row">
        <tr>
            <td class="data-cell left-gap">
                <p>NO: {{ $registro->id }}</p>
                <p>DISTRITO: <span class="uppercase">{{ $registro->distrito_nombre ?: 'SIN DISTRITO' }}</span></p>
                <p>MUNICIPIO: <span class="uppercase">{{ $registro->municipio_nombre ?: 'SIN MUNICIPIO' }}</span></p>

                <div class="spacer"></div>

                <p>LOCALIDAD: <span class="uppercase">{{ $registro->localidad_nombre ?: 'SIN LOCALIDAD' }}</span></p>

                <div class="spacer"></div>

                <p>ACTOR POLÍTICO:</p>
                <p class="uppercase">{{ $actorPolitico ?: 'SIN ACTOR POLÍTICO' }}</p>
            </td>

            <td class="data-cell right-gap">
                <p>SUJETO: <span class="uppercase">{{ $registro->sujeto_nombre ?: 'N/A' }}</span></p>

                <div class="spacer"></div>
                <div class="spacer"></div>

                <p>TIPO DE PUBLICIDAD: <span class="uppercase">{{ $registro->tipo_publicidad_nombre ?: 'SIN TIPO' }}</span></p>
            </td>
        </tr>
    </table>

    <table class="grid-table small-row">
        <tr>
            <td class="small-cell left-gap">
                <p>TAMAÑO: <span class="uppercase">{{ $registro->publicacion_medidas ?: 'SIN MEDIDAS' }}</span></p>
                <p>PROPIETARIO:</p>
                <p class="uppercase">{{ $actorPolitico ?: 'SIN PROPIETARIO' }}</p>
            </td>

            <td class="small-cell right-gap">
                <p>
                    REFERENCIA DOMICILIARIA:
                    <span class="uppercase">{{ $registro->referencia_domiciliaria ?: 'SIN REFERENCIA DOMICILIARIA' }}</span>
                </p>
            </td>
        </tr>
    </table>

    <div class="box observaciones">
        <p>
            OBSERVACIONES:
            <span class="uppercase">{{ $registro->observaciones ?: '' }}</span>
        </p>
    </div>
</body>
</html>
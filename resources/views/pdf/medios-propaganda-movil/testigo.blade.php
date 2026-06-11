<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Testigo propaganda móvil</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 10px; }
        h2 { font-size: 13px; margin: 16px 0 8px; border-bottom: 1px solid #d1d5db; padding-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; }
        td { border: 1px solid #d1d5db; padding: 6px; vertical-align: top; }
        .label { font-weight: bold; background: #f3f4f6; width: 28%; }
        .imagenes { margin-top: 12px; }
        .imagen { width: 48%; height: 180px; object-fit: contain; border: 1px solid #d1d5db; margin: 4px; }
    </style>
</head>
<body>
    <h1>Testigo de propaganda móvil #{{ $registro->id }}</h1>

    <h2>Datos generales</h2>
    <table>
        <tr><td class="label">Sujeto</td><td>{{ $registro->sujeto_nombre ?? 'Sin sujeto' }}</td></tr>
        <tr><td class="label">Organización</td><td>{{ $registro->organizacion_nombre ?? 'Sin organización' }}</td></tr>
        <tr><td class="label">Periodo</td><td>{{ $registro->periodo_nombre ?? 'Sin periodo' }}</td></tr>
        <tr><td class="label">Candidatura</td><td>{{ $registro->tipo_eleccion_nombre ?? 'Sin candidatura' }}</td></tr>
    </table>

    <h2>Datos del móvil</h2>
    <table>
        <tr><td class="label">Razón social</td><td>{{ $registro->razon_social ?? 'Sin razón social' }}</td></tr>
        <tr><td class="label">Unidad</td><td>{{ $registro->unidad ?? 'Sin unidad' }}</td></tr>
        <tr><td class="label">Número económico</td><td>{{ $registro->numero ?? 'Sin número' }}</td></tr>
        <tr><td class="label">Placa</td><td>{{ $registro->placa ?? 'Sin placa' }}</td></tr>
        <tr><td class="label">Ubicación</td><td>{{ $registro->municipio_nombre ?? 'Sin municipio' }} / {{ $registro->localidad_nombre ?? 'Sin localidad' }}</td></tr>
        <tr><td class="label">Vialidad</td><td>{{ $registro->vialidad ?? 'Sin vialidad' }}</td></tr>
        <tr><td class="label">Coordenadas</td><td>{{ $registro->latitud ?? 'S/L' }}, {{ $registro->longitud ?? 'S/L' }}</td></tr>
    </table>

    <h2>Publicación</h2>
    <table>
        <tr><td class="label">Tipo</td><td>{{ $registro->tipo_publicidad_nombre ?? 'Sin tipo' }}</td></tr>
        <tr><td class="label">Medidas</td><td>{{ $registro->publicacion_medidas ?? 'Sin medidas' }}</td></tr>
        <tr><td class="label">Versión</td><td>{{ $registro->publicacion_version ?? 'Sin versión' }}</td></tr>
        <tr><td class="label">Referencia</td><td>{{ $registro->referencia ?? 'Sin referencia' }}</td></tr>
        <tr><td class="label">Observaciones</td><td>{{ $registro->observaciones ?? 'Sin observaciones' }}</td></tr>
    </table>

    <h2>Imágenes</h2>
    <div class="imagenes">
        @forelse ($rutas_imagenes as $ruta)
            <img src="{{ $ruta }}" class="imagen" alt="Imagen de propaganda móvil">
        @empty
            <p>Sin imágenes disponibles.</p>
        @endforelse
    </div>
</body>
</html>

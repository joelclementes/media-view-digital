<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de capturas</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .meta {
            font-size: 11px;
            color: #4b5563;
            margin-bottom: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f3f4f6;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .total {
            font-weight: bold;
            background: #f9fafb;
        }
    </style>
</head>
<body>
    <h1>Reporte de capturas por usuario</h1>

    <div class="meta">
        <div><strong>Medio:</strong> {{ $medio }}</div>
        <div>
            <strong>Periodo:</strong>
            {{ $fecha_inicial ? \Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y') : 'Sin fecha inicial' }}
            -
            {{ $fecha_final ? \Carbon\Carbon::parse($fecha_final)->format('d/m/Y') : 'Sin fecha final' }}
        </div>
        <div><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Capturista</th>
                <th>Usuario / correo</th>
                <th class="text-center">Capturas</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($resultados as $resultado)
                <tr>
                    <td>{{ $resultado->nombre }}</td>
                    <td>{{ $resultado->email }}</td>
                    <td class="text-center">{{ $resultado->total }}</td>
                </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr class="total">
                <td colspan="2" class="text-right">Total</td>
                <td class="text-center">{{ $totalCapturas }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

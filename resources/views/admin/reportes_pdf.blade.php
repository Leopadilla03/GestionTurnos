<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Turnos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1, h2 { text-align: center; color: #E41E26; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #004B93; color: #fff; }
        .totales { font-weight: bold; background: #f5f5f5; }
    </style>
</head>
<body>
    <h1>Credi Q - Sistema de Gestión de Turnos</h1>
    <h2>{{ $ciudad }}, {{ $pais }}</h2>

    <h2>Reporte del {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</h2>

    <p><strong>Total de Turnos:</strong> {{ $totalTurnos }}</p>

    <h3>Turnos por Tipo</h3>
    <table>
        <tr><th>Tipo</th><th>Total</th></tr>
        @foreach ($turnosPorTipo as $item)
            <tr><td>{{ ucfirst($item->tipo) }}</td><td>{{ $item->total }}</td></tr>
        @endforeach
    </table>

    <h3>Turnos por Estado</h3>
    <table>
        <tr><th>Estado</th><th>Total</th></tr>
        @foreach ($turnosPorEstado as $item)
            <tr><td>{{ ucfirst($item->estado) }}</td><td>{{ $item->total }}</td></tr>
        @endforeach
    </table>

    <h3>Turnos por Departamento</h3>
    <table>
        <tr><th>Departamento</th><th>Total</th></tr>
        @foreach ($turnosPorDepto as $item)
            <tr><td>{{ $item->nombre }}</td><td>{{ $item->total }}</td></tr>
        @endforeach
    </table>

    @if(!empty($chartUrlDepto))
        <h3>Gráfica de Turnos por Departamento</h3>
            <img src="{{ $chartUrlDepto }}" style="width:100%; max-width:800px;">
        </div>
    @endif

    <h3 style="page-break-before: always;">Detalle de Turnos Atendidos</h3>
    @if(isset($turnosAtendidosDetalle) && $turnosAtendidosDetalle->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Estado</th>
                    <th>Departamento</th>
                    <th>Ventanilla</th>
                    <th>Sucursal</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($turnosAtendidosDetalle as $turno)
                    <tr>
                        <td>{{ $turno->numero ?? '-' }}</td>
                        <td>{{ ucfirst($turno->estado ?? '-') }}</td>
                        <td>{{ $turno->departamento ?? '-' }}</td>
                        <td>{{ $turno->ventanilla ?? '-' }}</td>
                        <td>{{ $turno->sucursal ?? '-' }}</td>
                        <td>{{ $turno->fecha ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hay turnos atendidos en el rango seleccionado.</p>
    @endif

    <footer style="text-align:center; margin-top:30px; font-size: 10px;">
        Generado el {{ now()->format('d/m/Y H:i:s') }}
    </footer>
</body>
</html>

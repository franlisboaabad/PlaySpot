<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Reservas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .filtros {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        .filtros p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: black; }
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-info { background-color: #17a2b8; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Reservas</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="filtros">
        <h3>Filtros Aplicados:</h3>
        <p><strong>Cancha:</strong> {{ $filtros['cancha'] }}</p>
        <p><strong>Fecha Desde:</strong> {{ $filtros['fecha_desde'] }}</p>
        <p><strong>Fecha Hasta:</strong> {{ $filtros['fecha_hasta'] }}</p>
        <p><strong>Estado:</strong> {{ ucfirst($filtros['estado']) }}</p>
        <p><strong>Total de Registros:</strong> {{ $reservas->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Horario</th>
                <th>Cancha</th>
                <th>Cliente</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Duración</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservas as $reserva)
                @php
                    $horaInicio = \Carbon\Carbon::parse($reserva->hora_inicio);
                    $horaFin = \Carbon\Carbon::parse($reserva->hora_fin);
                    $duracion = $horaInicio->diffInHours($horaFin);
                @endphp
                <tr>
                    <td>{{ $reserva->id }}</td>
                    <td>{{ $reserva->fecha->format('d/m/Y') }}</td>
                    <td>{{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</td>
                    <td>{{ $reserva->cancha->nombre }}</td>
                    <td>{{ $reserva->cliente->nombre }}</td>
                    <td>{{ $reserva->cliente->telefono }}</td>
                    <td>
                        @if($reserva->estado == 'confirmada')
                            <span class="badge badge-success">Confirmada</span>
                        @elseif($reserva->estado == 'pendiente')
                            <span class="badge badge-warning">Pendiente</span>
                        @elseif($reserva->estado == 'cancelada')
                            <span class="badge badge-danger">Cancelada</span>
                        @else
                            <span class="badge badge-info">Completada</span>
                        @endif
                    </td>
                    <td>{{ $duracion }} hrs</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">No hay reservas para los filtros seleccionados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>PlaySpot - Sistema de Reservas de Canchas</p>
        <p>Página 1</p>
    </div>
</body>
</html>


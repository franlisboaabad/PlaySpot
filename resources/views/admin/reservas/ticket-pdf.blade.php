<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Reserva #{{ $reserva->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .ticket {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            border: 2px solid #333;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #333;
        }
        .header p {
            font-size: 11px;
            color: #666;
        }
        .ticket-number {
            text-align: center;
            background-color: #f0f0f0;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px dashed #333;
        }
        .ticket-number strong {
            font-size: 18px;
            color: #007bff;
        }
        .section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        .section:last-child {
            border-bottom: none;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }
        .info-label {
            font-weight: bold;
            color: #555;
            width: 40%;
        }
        .info-value {
            color: #333;
            width: 60%;
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .status-confirmada {
            background-color: #28a745;
            color: white;
        }
        .status-pendiente {
            background-color: #ffc107;
            color: #333;
        }
        .status-cancelada {
            background-color: #dc3545;
            color: white;
        }
        .status-completada {
            background-color: #17a2b8;
            color: white;
        }
        .qr-placeholder {
            text-align: center;
            margin: 20px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border: 2px dashed #ccc;
        }
        .qr-placeholder p {
            font-size: 10px;
            color: #999;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #333;
            font-size: 10px;
            color: #666;
        }
        .footer p {
            margin: 3px 0;
        }
        .barcode {
            text-align: center;
            font-family: 'Courier New', monospace;
            font-size: 24px;
            letter-spacing: 3px;
            margin: 15px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .important-note {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            font-size: 11px;
        }
        .important-note strong {
            color: #856404;
        }
        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .ticket {
                border: none;
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Header -->
        <div class="header">
            <h1>PlaySpot</h1>
            <p>Sistema de Reservas de Canchas</p>
            <p>Ticket de Reserva</p>
        </div>

        <!-- Número de Ticket -->
        <div class="ticket-number">
            <strong>RESERVA #{{ str_pad($reserva->id, 6, '0', STR_PAD_LEFT) }}</strong>
        </div>

        <!-- Información de la Reserva -->
        <div class="section">
            <div class="section-title">Información de la Reserva</div>
            <div class="info-row">
                <span class="info-label">Fecha:</span>
                <span class="info-value">{{ $reserva->fecha->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Horario:</span>
                <span class="info-value">{{ $reserva->hora_inicio }} - {{ $reserva->hora_fin }}</span>
            </div>
            @php
                $horaInicio = \Carbon\Carbon::parse($reserva->hora_inicio);
                $horaFin = \Carbon\Carbon::parse($reserva->hora_fin);
                if ($horaFin->lt($horaInicio)) {
                    $horaFin->addDay();
                }
                $duracion = $horaInicio->diffInHours($horaFin);
            @endphp
            <div class="info-row">
                <span class="info-label">Duración:</span>
                <span class="info-value">{{ $duracion }} hora(s)</span>
            </div>
            <div class="info-row">
                <span class="info-label">Cancha:</span>
                <span class="info-value"><strong>{{ $reserva->cancha->nombre }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    <span class="status-badge status-{{ $reserva->estado }}">
                        {{ ucfirst($reserva->estado) }}
                    </span>
                </span>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="section">
            <div class="section-title">Datos del Cliente</div>
            <div class="info-row">
                <span class="info-label">Nombre:</span>
                <span class="info-value"><strong>{{ $reserva->cliente->nombre }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $reserva->cliente->telefono }}</span>
            </div>
            @if($reserva->cliente->email)
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $reserva->cliente->email }}</span>
            </div>
            @endif
            @if($reserva->cliente->dni)
            <div class="info-row">
                <span class="info-label">DNI:</span>
                <span class="info-value">{{ $reserva->cliente->dni }}</span>
            </div>
            @endif
        </div>

        <!-- Observaciones -->
        @if($reserva->observaciones)
        <div class="section">
            <div class="section-title">Observaciones</div>
            <p style="font-size: 11px; color: #555;">{{ $reserva->observaciones }}</p>
        </div>
        @endif

        <!-- Código de Barras -->
        <div class="barcode">
            {{ str_pad($reserva->id, 6, '0', STR_PAD_LEFT) }}-{{ $reserva->fecha->format('Ymd') }}
        </div>

        <!-- Nota Importante -->
        <div class="important-note">
            <strong>⚠️ IMPORTANTE:</strong><br>
            • Presente este ticket al momento de usar la cancha.<br>
            • La reserva es válida solo para la fecha y horario indicados.<br>
            • En caso de cancelación, contacte con anticipación.
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Generado el:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>Este es un comprobante de reserva generado automáticamente.</p>
            <p style="margin-top: 10px; font-size: 9px;">
                PlaySpot - Sistema de Reservas<br>
                Reserva #{{ str_pad($reserva->id, 6, '0', STR_PAD_LEFT) }}
            </p>
        </div>
    </div>
</body>
</html>


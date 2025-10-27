<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva Rechazada</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            line-height: 1.6;
            font-size: 14px;
            color: #333;
        }
        .container {
            margin: 20px;
        }
        .header {
            background-color: #ffc107;
            color: black;
            padding: 15px;
            border-radius: 6px 6px 0 0;
        }
        .content {
            border: 1px solid #ddd;
            border-top: none;
            padding: 20px;
            border-radius: 0 0 6px 6px;
        }
        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>Tu reserva ha sido rechazada</h2>
    </div>

    <div class="content">
        <p>Estimado/a <strong>{{ $reserva->solicitante->nombre }} {{ $reserva->solicitante->apellido }}</strong>,</p>

        <p>Lamentablemente, tu solicitud de reserva ha sido <strong>rechazada</strong>.</p>

        <p>Detalles de la solicitud:</p>
        <ul>
            <li><strong>Espacio solicitado:</strong> {{ $reserva->espacio->nombre }}</li>
            <li><strong>Fecha solicitada:</strong> {{ \Carbon\Carbon::parse($reserva->horario->fecha)->format('d/m/Y') }}</li>
            <li><strong>Horario:</strong> {{ $reserva->horario->hora_inicio }} - {{ $reserva->horario->hora_finalizar }}</li>
            <li><strong>Código de reserva:</strong> {{ $reserva->prefijo }}</li>
        </ul>

        @if(!empty($reserva->motivo_rechazo))
            <p><strong>Motivo del rechazo:</strong> {{ $reserva->motivo_rechazo }}</p>
        @else
            <p>Si deseas más información sobre esta decisión, puedes contactar con nuestro equipo.</p>
        @endif

        <p>Gracias por tu comprensión.</p>

        <p>Atentamente,<br>
        <strong>Departamento de Reservas</strong><br>
        MOPC - Club de Empleados</p>
    </div>

    <div class="footer">
        <p>Este mensaje es automático. Por favor, no respondas a este correo.</p>
    </div>
</div>

</body>
</html>

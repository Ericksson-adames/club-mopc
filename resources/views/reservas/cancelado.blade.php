<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva Cancelada</title>
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
            background-color: #dc3545;
            color: white;
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
        <h2>Tu reserva ha sido cancelada</h2>
    </div>

    <div class="content">
        <p>Estimado/a <strong>{{ $reserva->solicitante->nombre }} {{ $reserva->solicitante->apellido }}</strong>,</p>

        <p>Lamentamos informarte que tu reserva ha sido <strong>cancelada</strong>.</p>

        <p>A continuación te recordamos los detalles de tu reserva:</p>

        <ul>
            <li><strong>Espacio:</strong> {{ $reserva->espacio->nombre }}</li>
            <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->horario->fecha)->format('d/m/Y') }}</li>
            <li><strong>Horario:</strong> {{ $reserva->horario->hora_inicio }} - {{ $reserva->horario->hora_finalizar }}</li>
            <li><strong>Código de reserva:</strong> {{ $reserva->prefijo }}</li>
        </ul>

        <p>
            Si realizaste un pago o depósito, por favor comunícate con el departamento correspondiente
            para procesar el reembolso o reprogramación de la reserva.
        </p>

        <p>Te pedimos disculpas por los inconvenientes que esto pueda causar.</p>

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

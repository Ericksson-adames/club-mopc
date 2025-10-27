<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva Aprobada</title>
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
            background-color: #0d6efd;
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
        .btn {
            display: inline-block;
            padding: 10px 18px;
            background-color: #0d6efd;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h2>¡Tu reserva ha sido aprobada!</h2>
    </div>

    <div class="content">
        <p>Estimado/a <strong>{{ $reserva->solicitante->nombre }} {{ $reserva->solicitante->apellido }}</strong>,</p>

        <p>Nos complace informarte que tu solicitud de reserva ha sido <strong>aprobada</strong>.</p>

        <p>A continuación te compartimos los detalles:</p>

        <ul>
            <li><strong>Espacio reservado:</strong> {{ $reserva->espacio->nombre }}</li>
            <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->horario->fecha)->format('d/m/Y') }}</li>
            <li><strong>Horario:</strong> {{ $reserva->horario->hora_inicio }} - {{ $reserva->horario->hora_finalizar }}</li>
            <li><strong>Tipo de actividad:</strong> {{ $reserva->tipo_actividad }}</li>
            <li><strong>Código de reserva:</strong> {{ $reserva->prefijo }}</li>
        </ul>

        <p>
            Te recordamos que puedes revisar las condiciones del uso del espacio y los detalles de pago en el recibo adjunto
            o contactarnos para cualquier consulta adicional.
        </p>

        <p style="margin-top: 25px;">
            <a href="#" class="btn">Ver detalles en línea</a>
        </p>

        <p>¡Gracias por confiar en nuestro sistema de reservas!</p>

        <p>Atentamente,<br>
        <strong>Departamento de Reservas</strong><br>
        MOPC - Club de Empleados</p>
    </div>

    <div class="footer">
        <p>Este correo es generado automáticamente. Por favor, no respondas a este mensaje.</p>
    </div>
</div>

</body>
</html>

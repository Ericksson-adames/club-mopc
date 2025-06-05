<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Carta de Reserva</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <p style="text-align: right;">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>

    <p>Lic. Elio Amparo:</p>

    <p>
        Por medio de la presente, me dirijo a usted para solicitar la reserva del espacio requerido para llevar a cabo una actividad institucional programada. 
        Esta actividad está orientada al cumplimiento de nuestras funciones y responsabilidades en el marco de las atribuciones que nos corresponden.
    </p>

    <p>
        Agradezco de antemano su colaboración y quedo atento(a) a cualquier requerimiento adicional necesario para la confirmación de esta solicitud.
    </p>

    <p>Sin otro particular, me despido con sentimientos de alta estima y consideración.</p>

    <br><br><br>

    <p>Atentamente,</p>
    <strong>{{ $nombre }}</strong>

</body>
</html>

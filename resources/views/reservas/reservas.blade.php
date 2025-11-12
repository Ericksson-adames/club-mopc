<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resumen de Reserva</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 14px;
            margin: 40px;
            line-height: 1.6;
        }
        h2, h4 {
            text-align: center;
        }
        .section {
            margin-bottom: 25px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .info-table th, .info-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        ul {
            padding-left: 20px;
        }
    </style>
</head>
<body>

    <h2>Resumen de Reserva</h2>
    <p style="text-align: right;">Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>

    <div class="section">
        <h4>Datos del Solicitante</h4>
        <table class="info-table">
            <tr><th>Nombre completo</th><td>{{ $reserva->solicitante->nombre }} {{ $reserva->solicitante->apellido }}</td></tr>
            <tr><th>Teléfono</th><td>{{ $reserva->solicitante->numero_telefono }}</td></tr>
            <tr><th>Correo</th><td>{{ $reserva->solicitante->correo }}</td></tr>
            @if($reserva->solicitante->empresa !== 'no')
            <tr><th>Empresa</th><td>{{ $reserva->solicitante->empresa }}</td></tr>
            <tr><th>Departamento</th><td>{{ $reserva->solicitante->departamento }}</td></tr>
            <tr><th>Teléfono Empresa</th><td>{{ $reserva->solicitante->telefono_empresa }} Ext. {{ $reserva->solicitante->extesion }}</td></tr>
            @endif
        </table>
    </div>

    <div class="section">
        <h4>Detalles de la Reserva</h4>
        <table class="info-table">
            <tr><th>Espacio reservado</th><td>{{ $reserva->espacio->nombre }}</td></tr>
            <tr><th>Fecha</th><td>{{ \Carbon\Carbon::parse($reserva->horario->fecha)->format('d/m/Y') }}</td></tr>
            <tr><th>Horario</th><td>{{ $reserva->horario->hora_inicio }} - {{ $reserva->horario->hora_finalizar }}</td></tr>
            <tr><th>Tipo de actividad</th><td>{{ $reserva->tipo_actividad }}</td></tr>
            <tr><th>Número de invitados</th><td>{{ $reserva->numero_invitado }}</td></tr>
           {{-- <tr><th>Estado</th><td>{{ ucfirst($reserva->estado) }}</td></tr> --}}
            <tr><th>Codigo de pago</th><td>{{ $reserva->codigo_pago }}</td></tr>
            <tr><th>ID de reserva</th><td>{{ $reserva->prefijo }}</td></tr>
            
        </table>
    </div>

    <div class="section">
        <h4>Utilidades Solicitadas</h4>
        <table class="info-table">
            <tr><th>Tipo</th><td>{{ ucfirst($reserva->adicional->utilidades) }}</td></tr>
            @if(in_array($reserva->adicional->utilidades, ['sillas', 'ambos']))
            <tr><th>Sillas</th><td>{{ $reserva->adicional->sillas }}</td></tr>
            @endif
            @if(in_array($reserva->adicional->utilidades, ['mesas', 'ambos']))
            <tr><th>Mesas</th><td>{{ $reserva->adicional->mesas }}</td></tr>
            @endif
            <tr><th>Total de utilidades</th><td>{{ $reserva->adicional->total_utilidades }}</td></tr>
        </table>
    </div>

    <div class="section">
        <h4>Condiciones y Recomendaciones</h4>
        <ul>
            <li><strong>No se permite la entrada de comidas ni bebidas.</strong> Deben ser facturadas en la cafetería del MOPC.</li>
            <li>Depósito de garantía: <strong>RD$2,000.00</strong> (reembolsable si el área se entrega en orden). Este monto no cubre el costo de uso del espacio.</li>
            <li>Para dudas, comunicarse con la Sra. Janna al <strong>(809) 904-1273</strong>, ext. <strong>9503-2217</strong>.</li>
            <li>Cancelar con anticipación si no se realizará la actividad.</li>
            <li>El pago debe realizarse mínimo <strong>5 días antes</strong> de la fecha reservada.</li>
            <li>Respetar las instalaciones del CLUB. No se permiten daños.</li>
            <li>Entregar el área limpia y retirar mobiliario al finalizar.</li>
            <li>Coordinar alimentos y bebidas con la Sra. María José al <strong>809-977-2810</strong>.</li>
        </ul>
    </div>

</body>
</html>

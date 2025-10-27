<x-filament::page>
    <div class="space-y-6">

        <x-filament::section heading="Datos del Solicitante">
            <x-slot name="header">
                <h2 class="text-xl font-bold">Datos del Solicitante</h2>
            </x-slot>
            <ul class="space-y-1">
                <li><strong>Nombre:</strong> {{ $reserva->solicitante->nombre }} {{ $reserva->solicitante->apellido }}</li>
                <li><strong>Teléfono:</strong> {{ $reserva->solicitante->numero_telefono }}</li>
                <li><strong>Correo:</strong> {{ $reserva->solicitante->correo }}</li>
                @if($reserva->solicitante->empresa !== 'no')
                    <li><strong>Empresa:</strong> {{ $reserva->solicitante->empresa }}</li>
                    <li><strong>Departamento:</strong> {{ $reserva->solicitante->departamento }}</li>
                    <li><strong>Teléfono empresa:</strong> {{ $reserva->solicitante->telefono_empresa }} Ext. {{ $reserva->solicitante->extesion }}</li>
                @endif
            </ul>
        </x-filament::section>

        <x-filament::section heading="Detalles de la Reserva">
            <x-slot name="header">
                <h2 class="text-xl font-bold">Detalles de la Reserva</h2>
            </x-slot>
            <ul class="space-y-1">
                <li><strong>Espacio reservado:</strong> {{ $reserva->espacio->nombre }}</li>
                <li><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($reserva->horario->fecha)->format('d/m/Y') }}</li>
                <li><strong>Horario:</strong> {{ $reserva->horario->hora_inicio }} - {{ $reserva->horario->hora_finalizar }}</li>
                <li><strong>Tipo de actividad:</strong> {{ $reserva->tipo_actividad }}</li>
                <li><strong>Número de invitados:</strong> {{ $reserva->numero_invitado }}</li>
                <li><strong>Estado:</strong> 
                    <x-filament::badge color="{{ $reserva->estado === 'pendiente' ? 'warning' : ($reserva->estado === 'aprobado' ? 'success' : 'danger') }}">
                        {{ ucfirst($reserva->estado) }}
                    </x-filament::badge>
                </li>
            </ul>
        </x-filament::section>

        <x-filament::section heading="Utilidades">
            <x-slot name="header">
                <h2 class="text-xl font-bold">Utilidades</h2>
            </x-slot>
            <ul class="space-y-1">
                <li><strong>Tipo:</strong> {{ ucfirst($reserva->adicional->utilidades) }}</li>
                @if(in_array($reserva->adicional->utilidades, ['sillas', 'ambos']))
                    <li><strong>Sillas:</strong> {{ $reserva->adicional->sillas }}</li>
                @endif
                @if(in_array($reserva->adicional->utilidades, ['mesas', 'ambos']))
                    <li><strong>Mesas:</strong> {{ $reserva->adicional->mesas }}</li>
                @endif
                <li><strong>Total de utilidades:</strong> {{ $reserva->adicional->total_utilidades }}</li>
            </ul>
        </x-filament::section>

        <x-filament::section heading="Información de pago">
            <x-slot name="header">
                <h2 class="text-xl font-bold">Información del Sistema</h2>
            </x-slot>
            <ul class="space-y-1">
                <li><strong>ID de reserva:</strong> {{ $reserva->prefijo }}</li>
                <li><strong>Código de pago:</strong> {{ $reserva->codigo_pago }}</li>
                <!--li><strong>Fecha de emisión:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</li-->
            </ul>
        </x-filament::section>
        {{--  
        <x-filament::section heading="Condiciones y Recomendaciones">
            <x-slot name="header">
                <h2 class="text-xl font-bold">Condiciones y Recomendaciones</h2>
            </x-slot>
            <ul class="list-disc ml-5 space-y-1 text-sm">
                <li><strong>No se permite la entrada de comidas ni bebidas:</strong> deben ser facturadas en la cafetería del MOPC.</li>
                <li>Depósito de garantía: <strong>RD$2,000.00</strong> (reembolsable si el área se entrega en orden). Este monto no cubre el costo de uso del espacio.</li>
                <li>Para dudas, comunicarse con la Sra. Janna al <strong>(809) 904-1273</strong>, ext. <strong>9503-2217</strong>.</li>
                <li>Cancelar con anticipación si no se realizará la actividad.</li>
                <li>El pago debe realizarse mínimo <strong>5 días antes</strong> de la fecha reservada.</li>
                <li>Respetar las instalaciones del CLUB. No se permiten daños.</li>
                <li>Entregar el área limpia y retirar mobiliario al finalizar.</li>
                <li>Coordinar alimentos y bebidas con la Sra. María José al <strong>809-977-2810</strong>.</li>
            </ul>
        </x-filament::section>
        --}}

    </div>
</x-filament::page>
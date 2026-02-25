<?php

namespace App\Filament\Resources\ReservasResource\Pages;

use App\Filament\Resources\ReservasResource;
use App\Mail\ReservaAprobado;
use App\Mail\reservaCancelada;
use App\Mail\reservaRechazada;
use App\Models\reservas;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EditReservas extends EditRecord
{
    protected static string $resource = ReservasResource::class;

   /* protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }*/

    /*protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }*/

    // funcion para precargar los datos de otras tablas 
    protected function mutateFormDataBeforeFill(array $data): array
{
    $data = parent::mutateFormDataBeforeFill($data);

    $solicitante = $this->record->solicitante;
    $horario = $this->record->horario;
    $adicional = $this->record->adicional;

    if ($solicitante) {
        $data['nombre'] = $solicitante->nombre;
        $data['apellido'] = $solicitante->apellido;
        $data['numero_telefono'] = $solicitante->numero_telefono;
        $data['correo'] = $solicitante->correo;
        /*$data['tiene_empresa'] = $solicitante->tiene_empresa;
        $data['empresa'] = $solicitante->empresa;
        $data['departamento'] = $solicitante->departamento;
        $data['telefono_empresa'] = $solicitante->telefono_empresa;
        $data['extesion'] = $solicitante->extesion;
        //$data['numero_invitado'] = $solicitante->numero_invitado;*/
    }

    if ($horario) {
        $data['fecha'] = $horario->fecha;
        $data['hora_inicio'] = $horario->hora_inicio;
        $data['hora_finalizar'] = $horario->hora_finalizar;
    }

    if ($adicional) {
        $data['utilidades'] = $adicional->utilidades;
        $data['sillas'] = $adicional->sillas;
        $data['mesas'] = $adicional->mesas;
        $data['total_utilidades'] = $adicional->total_utilidades;
    }

    return $data;
}

// funcion para guardar los datos actuaalizados en las otras tablas
protected function afterSave(): void
{
    $data = $this->form->getState();

    $this->record->solicitante()->updateOrCreate([], [
        'nombre' => $data['nombre'],
        'apellido' => $data['apellido'],
        'numero_telefono' => $data['numero_telefono'],
        'correo' => $data['correo'],
        /*'tiene_empresa' => $data['tiene_empresa'],
        'empresa' => $data['empresa'],
        'departamento' => $data['departamento'],
        'telefono_empresa' => $data['telefono_empresa'],
        'extesion' => $data['extesion'],*/
        //'numero_invitado' => $data['numero_invitado'],
    ]);


     $fecha = $data['fecha'];
       $horaInicio = $data['hora_inicio'];
       $horaFinalizar = $data['hora_finalizar'];
    $this->record->horario()->updateOrCreate([], [
       'fecha' => $fecha,
       'hora_inicio' => "{$fecha} {$horaInicio}",
       'hora_finalizar' => "{$fecha} {$horaFinalizar}",
    ]);

    $this->record->adicional()->updateOrCreate([], [
        'utilidades' => $data['utilidades'],
        'sillas' => $data['sillas'] ?? 0,
        'mesas' => $data['mesas'] ?? 0,
        'total_utilidades' => $data['total_utilidades'],
    ]);
}
 
// funcion para eliminar los datos de las otras tablas porque pertenecen a otras tablas diferente a la tabla reservas (opcional)
protected function mutateFormDataBeforeSave(array $data): array
{
    unset(
        $data['nombre'],
        $data['apellido'],
        $data['numero_telefono'],
        $data['correo'],
       /* $data['tiene_empresa'],
        $data['empresa'],
        $data['departamento'],
        $data['telefono_empresa'],
        $data['extesion'],
        //$data['numero_invitado'],*/
        $data['fecha'],
        $data['hora_inicio'],
        $data['hora_finalizar'],
        $data['utilidades'],
        $data['sillas'],
        $data['mesas'],
        $data['total_utilidades']
    );

    return $data;
}


protected function handleRecordUpdate(Model $record, array $data): Model
{
    // Actualizar el registro
    $record->update($data);

    // Mapa de estados a sus Mailables correspondientes
    $mailables = [
        'rechazado' => reservaRechazada::class,
        'cancelado' => reservaCancelada::class, // opcional si quieres manejar también aprobaciones
    ];

    // Si el estado actual tiene un correo asociado
    if (array_key_exists($record->estado, $mailables)) {
        try {
            $reserva = reservas::with(['solicitante', 'horario', 'adicional', 'espacio'])
                ->findOrFail($record->id);

            $correo = data_get($reserva, 'solicitante.correo');

            if ($correo) {
                $mailableClass = $mailables[$record->estado];
                Mail::to($correo)->send(new $mailableClass($reserva));
            } else {
                Log::warning("Reserva {$record->estado} sin correo de solicitante", [
                    'reserva_id' => $record->id,
                ]);
            }
        } catch (\Symfony\Component\Mailer\Exception\TransportExceptionInterface $e) {
            Log::error("Error SMTP al enviar correo de reserva {$record->estado}", [
                'reserva_id' => $record->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    return $record;
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}


}

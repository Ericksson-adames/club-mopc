<?php

namespace App\Filament\Resources\ReservasResource\Pages;

use App\Filament\Resources\ReservasResource;
use App\Mail\cartaReserva;
use App\Mail\reciboReserva;
use App\Models\adicional;
use App\Models\carta;
use App\Models\horario;
use App\Models\reservas;
use App\Models\solicitante;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateReservas extends CreateRecord
{
    protected static string $resource = ReservasResource::class;
    
    //funcion para crear un nuevo registro automaatico de silla y mesa con el valor 0
   /* protected function mutateFormDataBeforeCreate(array $data): array
{
    if (($data['utilidades'] ?? null) === 'ninguno') {
        $data['sillas'] = 0;
        $data['mesas'] = 0;
    }
    $data['sillas'] = $data['sillas'] ?? 0;
    $data['mesas'] = $data['mesas'] ?? 0;
    $data['total_utilidades'] = $data['total_utilidades'] ?? 0;

    return $data;
}*/
      // funcion para crear los registro para la reserva 
      protected function handleRecordCreation(array $data): Model
      {
       $solicitante = solicitante::create([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'numero_telefono' => $data['numero_telefono'],
            'correo' => $data['correo'],
            'empresa' => $data['empresa']?? 'no',
            'departamento' => $data['departamento']?? 'no',
            'extesion' => $data['extesion'] ?? '0000',
            'telefono_empresa' => $data['telefono_empresa'] ?? '0000000000',
        ]);

        // 2. Crear el horario
       $fecha = $data['fecha'];
       $horaInicio = $data['hora_inicio'];
       $horaFinalizar = $data['hora_finalizar'];

       $horario = Horario::create([
       'fecha' => $fecha,
       'hora_inicio' => "{$fecha} {$horaInicio}",
       'hora_finalizar' => "{$fecha} {$horaFinalizar}",
       ]);

        // 3. Crear adicional
        $adicional = adicional::create([
            'utilidades' => $data['utilidades'],
            'sillas' => $data['sillas'] ?? 0,
            'mesas' => $data['mesas'] ?? 0,
            'total_utilidades' => $data['total_utilidades'] ?? 0,
        ]);

        // 4. Concatenar nombre + apellido para la carta
    $nombreCompleto = $solicitante->nombre . ' ' . $solicitante->apellido;

    // 5. Generar PDF
    $nombreArchivo = 'carta_' . Str::slug($nombreCompleto) . '_' . time() . '.pdf';

    $pdf = Pdf::loadView('cartas.carta_reserva', [
        'nombre' => $nombreCompleto,
    ]);

    $pdf->save(storage_path('app/public/cartas/' . $nombreArchivo));

    // 6. Guardar carta
    $carta = Carta::create([
        'nombre_pdf' => $nombreArchivo,
    ]);
    // Enviar correo con la carta adjunta
    $destinatario = 'ericksonadamesabreu@gmail.com'; // <-- cámbialo
Mail::to($destinatario)
    ->send(new cartaReserva($nombreCompleto, $pdf));
  

        // 7. Finalmente, crear la reserva usando los IDs de los anteriores
        $reserva = reservas::create([
            'id_usuario' => Auth::user()->id,
            'id_espacio' => $data['espacio'], //->relationship('espacio', 'nombre')
            'id_solicitante' => $solicitante->id,
            'id_horario' => $horario->id,
            'id_adicional' => $adicional->id,
            'id_carta' => $carta->id,
            'numero_invitado' => $data['numero_invitado'],
            'tipo_actividad' => $data['tipo_actividad'],
            'estado' => 'pendiente', // opcional(en la BD ya esta definido por defaul)
            
            

        ]);
        

        $reserva->refresh();

          
        // -------------------- GENERAR RECIBO --------------------
        //relacion para el recibo de la reserva 
        $reserva->load([
         'usuario',
         'espacio',
         'horario',
         'adicional',
         'solicitante',
       ]);

       //genera el pdf
       $reciboNombreArchivo = 'recibo_' . Str::slug($nombreCompleto) . '_' . time() . '.pdf';

       //aqui se guarda el html del recibo
       $pdf = Pdf::loadView('reservas.reservas', [
         'reserva' => $reserva
       ]);
       //enviar correo con el recibo de la reserva
         $correo = data_get($reserva, 'solicitante.correo'); // 
      Mail::to($correo)
    ->send(new reciboReserva($reserva));


       //aqui se guarda el archivo del recibo
       $pdf->save(storage_path('app/public/recibos/' . $reciboNombreArchivo));

        return $reserva;

      
    }

  protected function afterCreate(): void
{
    //xdebug_break();

    $reserva = $this->record->loadMissing(['horario','espacio']);

    $recipient = Filament::auth()->user();
    if (! $recipient) {
        Log::warning('Notif BD: sin usuario de Filament autenticado');
        return;
    }

    $fecha   = optional($reserva->horario)->fecha
        ? Carbon::parse($reserva->horario->fecha)->format('d/m/Y') : '—';
    $espacio = optional($reserva->espacio)->nombre ?? '—';

    // 1) Toast en pantalla (para ti)
    Notification::make()
        ->title('Reserva creada')
        ->body("Nueva **reserva pendiente** para **{$fecha}** en **{$espacio}**.")
        ->success()
        ->send();

    //2) Guardar en BD (campanita)
    try {
        Notification::make()
            ->title('Reserva creada')
            ->body("Nueva **reserva pendiente** para **{$fecha}** en **{$espacio}**.")
            ->success()
            ->sendToDatabase($recipient);
    } catch (\Throwable $e) {
        Log::error('Notif BD: fallo al guardar', ['e' => $e->getMessage()]);
    }
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}

}

<?php

namespace App\Filament\Resources\ReservasResource\Pages;

use App\Filament\Resources\ReservasResource;
use App\Models\adicional;
use App\Models\carta;
use App\Models\horario;
use App\Models\reservas;
use App\Models\solicitante;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
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
  

        // 7. Finalmente, crear la reserva usando los IDs de los anteriores
        $reserva = reservas::create([
            'id_usuario' => Auth::id(),
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

       //aqui se guarda el archivo del recibo
       $pdf->save(storage_path('app/public/recibos/' . $reciboNombreArchivo));

        return $reserva;
    }

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}

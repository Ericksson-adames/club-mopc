<?php

namespace App\Filament\Resources\ReservasResource\Pages;

use App\Filament\Resources\ReservasResource;
use App\Models\adicional;
use App\Models\carta;
use App\Models\horario;
use App\Models\reservas;
use App\Models\solicitante;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

use Illuminate\Database\Eloquent\Model;

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
            'empresa' => $data['empresa'],
            'departamento' => $data['departamento'],
            'extesion' => $data['extesion'],
            'telefono_empresa' => $data['telefono_empresa'],
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

        // 4. Crear carta
        $carta = carta::create([
            'nombre_pdf' => $data['nombre_pdf'],
        ]);

        // 5. Finalmente, crear la reserva usando los IDs de los anteriores
        $reserva = reservas::create([
            'id_usuario' => Auth::id(),
            'id_espacio' => $data['espacio'], //->relationship('espacio', 'nombre')
            'id_solicitante' => $solicitante->id,
            'id_horario' => $horario->id,
            'id_adicional' => $adicional->id,
            'id_carta' => $carta->id,
            'numero_invitado' => $data['numero_invitado'],
            'estado' => 'pendiente', // opcional(en la BD ya esta definido por defaul)

        ]);

        return $reserva;
}

protected function getRedirectUrl(): string
{
    return $this->getResource()::getUrl('index');
}
}

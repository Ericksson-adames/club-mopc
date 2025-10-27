<?php

namespace App\Filament\Resources\PagosResource\Pages;

use App\Filament\Resources\PagosResource;
use App\Mail\ReservaAprobado;
use App\Models\pagos;
use App\Models\reservas;
use Filament\Actions;
use Filament\Facades\Filament;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException as ValidationValidationException;

class CreatePagos extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = PagosResource::class;
    
    //funcion para crear pagos 
    protected function handleRecordCreation(array $data): Model
    {
        /*$recipient = Filament::auth()->user();

         Notification::make()
         ->title('Saved successfully')
         ->sendToDatabase($recipient);*/
       
            //condision para verifica si el codigo pago existe en la tabla pagos column codigo_pago
            if(pagos::where('codigo_pago', $data['codigo_pago'])->exists()){
                 Notification::make()
            ->title('Error')
            ->body('Este código de pago ya fue utilizado.')
            ->danger()
            ->send();
                 throw ValidationException::withMessages([
            'codigo_pago' => 'Este código de pago ya fue utilizado.',
        ]);
        
    }
     //condision para verifica si el codigo reservas existe en la tabla pagos column codigo_pago
            if (!Reservas::where('codigo_pago', $data['codigo_pago'])->exists()) {
    Notification::make()
        ->title('Error')
        ->body('El código de pago ingresado no es válido.')
        ->danger()
        ->send();

    throw ValidationException::withMessages([
        'codigo_pago' => 'El código de pago ingresado no es válido.',
    ]);
}
        $pago = pagos::create([
            'monto' => $data['monto'],
            'codigo_pago' => $data['codigo_pago'],
            'metodo_pago' => $data['metodo_pago'],
            'id_reserva' => $data['id_reserva'],
        ]);

        reservas::where('id', $data['id_reserva'])->update([
            'estado' => 'aprobado',
        ]);
        $reserva = reservas::with('solicitante', 'horario', 'adicional','espacio')->find($data['id_reserva']);

        $correo = data_get($reserva, 'solicitante.correo'); // 
      Mail::to($correo)
    ->send(new ReservaAprobado ($reserva));



        return $pago;
    
    }
    protected function afterCreate(): void
    {
        //$pagos = $this->record->loadMissing(['reservas']);
        Notification::make()
            ->title('Reserva creada')
            ->body("Nueva **reserva pendiente** para **{}** en **{}**.")
            ->success()
            ->sendToDatabase(Filament::auth()->user());
    }
   /* public function canCreateAnother(): bool{
        $data = $this->form->getState();
        return isset($data['codigo_valido']) && $data['codigo_valido'] == true;
    }*/
}

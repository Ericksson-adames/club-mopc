<?php

namespace App\Filament\Resources\PagosResource\Pages;

use App\Filament\Resources\PagosResource;
use App\Models\pagos;
use App\Models\reservas;
use Filament\Actions;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
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

        return $pago;
    
    }
   /* public function canCreateAnother(): bool{
        $data = $this->form->getState();
        return isset($data['codigo_valido']) && $data['codigo_valido'] == true;
    }*/
}

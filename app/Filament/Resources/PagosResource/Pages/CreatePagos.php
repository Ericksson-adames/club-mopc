<?php

namespace App\Filament\Resources\PagosResource\Pages;

use App\Filament\Resources\PagosResource;
use App\Models\pagos;
use App\Models\reservas;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

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
        try{
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
    } catch(\Throwable $e){
        logger()->error($e);
        dd('ERROR:'. $e->getMessage(), $e->getTraceAsString());
    }
    }
   /* public function canCreateAnother(): bool{
        $data = $this->form->getState();
        return isset($data['codigo_valido']) && $data['codigo_valido'] == true;
    }*/
}

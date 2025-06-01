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
    protected static string $resource = PagosResource::class;

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
    protected function getRedirectUrl(): string
    {
        return $this->getRedirectUrl():: getUrl('index');
    }
}

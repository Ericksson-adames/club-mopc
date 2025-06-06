<?php

namespace App\Filament\Resources\ReservasResource\Pages;

use App\Filament\Resources\ReservasResource;
use Filament\Actions;
use Filament\Resources\Pages\view;
use Filament\Resources\Pages\ViewRecord;

class ViewReserva extends ViewRecord
{
    protected static string $resource = ReservasResource::class;

    protected function getViewData(): array
    {
        return [
            'reserva' => $this->record->load([
                'solicitante',
                'usuario',
                'espacio',
                'horario',
                'adicional',
                'carta',
                'pagos',
                'historial_reservas',

            ])
        ];
    }

    protected static string $view = 'view-reserva';
}

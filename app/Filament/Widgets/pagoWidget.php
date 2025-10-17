<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class pagoWidget extends BaseWidget
{
         protected function getStats(): array
    {
        return [
            //
            Stat::make('Total de pagos', $this->getPagos()),
            Stat::make('Total de reembolso', $this->getReembolso()),
            Stat::make('Monto total', $this->getMontoTotal()),
        ];
    }

    protected function getPagos()
    {
        return \App\Models\Pagos::where('estado', 'pago')->count();
    }
    protected function getReembolso()
    {
        return \App\Models\Pagos::where('estado', 'reembolso')->count();
    }

    protected function getMontoTotal()
    {
        return \App\Models\Pagos::where('estado', 'pago')->sum('monto');
    }
}

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
            Stat::make('Pagos', $this->getPagos())
            ->icon('heroicon-o-credit-card')
            ->description('Total de pagos')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('primary'),
            
            Stat::make('Reembolso', $this->getReembolso())
            ->icon('heroicon-c-receipt-refund')
            ->description('Total de reembolsos')
            ->descriptionIcon('heroicon-m-arrow-trending-down')
            ->color('danger'),

            Stat::make('Monto total', $this->getMontoTotal())
            ->icon('heroicon-o-currency-dollar')
            ->description('Total de pagos')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->color('success'),
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

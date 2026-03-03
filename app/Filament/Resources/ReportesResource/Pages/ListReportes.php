<?php

namespace App\Filament\Resources\ReportesResource\Pages;

use App\Filament\Resources\ReportesResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\CustomerResource\Widgets\reservasWidget;
use App\Filament\Resources\CustomerResource\Widgets\graficoWigets;
use App\Filament\Resources\CustomerResource\Widgets\clienteWigets;

class ListReportes extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = ReportesResource::class;
    

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear Reporte'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            reservasWidget::class,
            graficoWigets::class,
            clienteWigets::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos'),
            'aprobada'=>Tab::make('aprobadas')->query(fn ($query) => $query->where('estado', 'aprobado')),
            'pendientes'=>Tab::make('Pendientes')->query(fn ($query) => $query->where('estado', 'pendiente')),
            'canceladas'=>Tab::make('Canceladas')->query(fn ($query) => $query->where('estado', 'cancelado')),
            'rechazadas'=>Tab::make('Rechazadas')->query(fn ($query) => $query->where('estado', 'rechazado')),
        ];
    }
    
}

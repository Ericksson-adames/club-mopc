<?php

namespace App\Filament\Resources\ReportesResource\Pages;

use App\Filament\Resources\ReportesResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;

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
            \App\Filament\Widgets\reservasWidget::class,
            \App\Filament\Widgets\graficoWigets::class,
            \App\Filament\Widgets\clienteWigets::class,
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

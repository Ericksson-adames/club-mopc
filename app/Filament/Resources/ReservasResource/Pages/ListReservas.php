<?php

namespace App\Filament\Resources\ReservasResource\Pages;

use App\Filament\Resources\ReservasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;

class ListReservas extends ListRecords
{
    protected static string $resource = ReservasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
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

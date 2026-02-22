<?php

namespace App\Filament\Resources\EspacioResource\Pages;

use App\Filament\Resources\EspacioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;

class ListEspacios extends ListRecords
{
    protected static string $resource = EspacioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear Espacio'),
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos'),
            'activo'=>Tab::make('Activo')->query(fn ($query) => $query->where('estado', 'activo')),
            'inactivo'=>Tab::make('Inactivo')->query(fn ($query) => $query->where('estado', 'inactivo')),
            'mantenimiento'=>Tab::make('Mantenimiento')->query(fn ($query) => $query->where('estado', 'mantenimiento')),
        ];
    }
}

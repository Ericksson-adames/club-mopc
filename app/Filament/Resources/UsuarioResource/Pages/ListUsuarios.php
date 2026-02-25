<?php

namespace App\Filament\Resources\UsuarioResource\Pages;

use App\Filament\Resources\UsuarioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
 use Filament\Resources\Pages\ListRecords\Tab;

class ListUsuarios extends ListRecords
{
    protected static string $resource = UsuarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear Usuario'),
        ];
    }

   public function getTabs(): array
    {
       return [
            null => Tab::make('Todos'),
            'activo'=>Tab::make('Activo')->query(fn ($query) => $query->where('estado', 'activo')),
            'inactivo'=>Tab::make('Inactivo')->query(fn ($query) => $query->where('estado', 'inactivo')),
        ];
    }
}

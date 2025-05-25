<?php

namespace App\Filament\Resources\UsuarioResource\Pages;

use App\Filament\Resources\UsuarioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUsuario extends CreateRecord
{
    protected static string $resource = UsuarioResource::class;

    //funcion para volver atras despues de crear un usuario
    protected function  getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
}

<?php

namespace App\Filament\Resources\UsuarioResource\Pages;

use App\Filament\Resources\UsuarioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsuario extends EditRecord
{
    protected static string $resource = UsuarioResource::class;
    
    //funcion para volver a la pagina despues de editar un usuario
    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }

    /*otected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }*/
}

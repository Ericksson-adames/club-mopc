<?php

namespace App\Filament\Resources\EspacioResource\Pages;

use App\Filament\Resources\EspacioResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateEspacio extends CreateRecord
{
    protected static string $resource = EspacioResource::class;

   /* protected function afterCreate(): void
    {

Notification::make()
    ->title('Hola')
    ->body('Notificación desde Filament')
    ->sendToDatabase( Filament::auth()->user());
}*/

    protected function getRedirectUrl(): string
    {
     return $this->getResource()::getUrl('index');   
    }
}

<?php

namespace App\Filament\Resources\EspacioResource\Pages;

use App\Filament\Resources\EspacioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEspacio extends EditRecord
{
    protected static string $resource = EspacioResource::class;

    protected function getRedirectUrl(): ?string
    {
        return $this->getResource()::getUrl('index');
    }

    /*protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }*/
}

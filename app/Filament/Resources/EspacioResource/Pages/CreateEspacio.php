<?php

namespace App\Filament\Resources\EspacioResource\Pages;

use App\Filament\Resources\EspacioResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEspacio extends CreateRecord
{
    protected static string $resource = EspacioResource::class;

    protected function getRedirectUrl(): string
    {
     return $this->getResource()::getUrl('index');   
    }
}

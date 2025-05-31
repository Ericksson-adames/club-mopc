<?php

namespace App\Filament\Resources\ReservasResource\Pages;

use App\Filament\Resources\ReservasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservas extends EditRecord
{
    protected static string $resource = ReservasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

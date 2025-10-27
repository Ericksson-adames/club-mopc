<?php

namespace App\Filament\Resources\ReportesResource\Pages;

use App\Filament\Resources\ReportesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReportes extends EditRecord
{
    protected static string $resource = ReportesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ReportesResource\Pages;

use App\Filament\Resources\ReportesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReportes extends ListRecords
{
    protected static string $resource = ReportesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

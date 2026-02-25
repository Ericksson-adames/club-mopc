<?php

namespace App\Filament\Resources\PagosResource\Pages;

use App\Filament\Resources\PagosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\App;

class ListPagos extends ListRecords
{ 
    protected static string $resource = PagosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Crear Pago'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\pagoWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Todos'),
            'reembolso'=>Tab::make('Reembolsos')->query(fn ($query) => $query->where('estado', 'reembolso')),
            'pago'=>Tab::make('Pagos')->query(fn ($query) => $query->where('estado', 'pago')),
        ];
    } 

   
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagosResource\Pages;
use App\Filament\Resources\PagosResource\RelationManagers;
use App\Models\Pagos;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PagosResource extends Resource
{
    protected static ?string $model = Pagos::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('monto')
                ->numeric()
                ->prefix('$')
                ->rules(['numeric', 'min:0'])
                ->required(),
                 Forms\Components\TextInput::make('codigo_pago')
                ->required(),
                 Forms\Components\TextInput::make('id_reserva')
                ->required(),
                 Forms\Components\Select::make('metodo_pago')
                 ->options([
                    'efectivo' => 'Efectivo',
                    'tarjeta' => 'Tarjeta',
                    'transferencia' => 'Transferencia',
                 ])
                ->required(),
                 Forms\Components\Select::make('estado')
                 ->visibleOn('edit')
                 ->options([
                    'pendiente' => 'Pendiente',
                    'pago' => 'Pago',
                    'impago' => 'Impago',
                 ])
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('prefijo')
                ->searchable()
                ->sortable()
                ->label('ID'),
                Tables\Columns\TextColumn::make('monto')
                ->searchable()
                ->sortable()
                ->label('Monto'),
                 Tables\Columns\TextColumn::make('metodo_pago')
                ->searchable()
                ->sortable()
                ->label('Tipo'),
                 Tables\Columns\TextColumn::make('create_at')
                ->searchable()
                ->dateTime()
                ->sortable()
                ->label('Creado'),
                 Tables\Columns\TextColumn::make('updated_at')
                ->searchable()
                ->dateTime()
                ->sortable()
                ->label('Actualizado')
                ->toggleable(isToggledHiddenByDefault: true),
                 Tables\Columns\TextColumn::make('estado')
                ->searchable()
                ->sortable()
                ->label('Estado')
            ])
            ->filters([
                //
                SelectFilter::make('estado')
                ->options([
                    'pago' => 'Pago',
                    'impago' => 'Impago',
                ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPagos::route('/'),
            'create' => Pages\CreatePagos::route('/create'),
            'edit' => Pages\EditPagos::route('/{record}/edit'),
        ];
    }
}

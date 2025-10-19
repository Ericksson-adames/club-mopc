<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EspacioResource\Pages;
use App\Filament\Resources\EspacioResource\RelationManagers;
use App\Models\Espacio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EspacioResource extends Resource
{
    protected static ?string $model = Espacio::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationGroup = 'Gestión';

    protected static ?int $navigationShort = 3;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('nombre')
                ->required(),
                Forms\Components\TextInput::make('ubicacion')
                ->required(),
                Forms\Components\TextInput::make('capacidad')
                ->numeric()
                ->required(),
                Forms\Components\Select::make('estado')
                ->visibleOn('edit')
                ->options([
                    'activo' => 'Activo',
                    'mantenimiento' => 'Mantenimiento',
                    'inactivo' => 'Inactivo',
                ])
                ->required(),
                 Forms\Components\Textarea::make('descripcion')
                ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('nombre')
                ->sortable()
                ->label('Nombre')
                ->searchable(),
                Tables\Columns\TextColumn::make('ubicacion')
                 ->sortable()
                ->label('Ubicacuion')
                ->searchable(),
                 Tables\Columns\TextColumn::make('capacidad')
                ->sortable()
                ->label('Capacidad')
                ->searchable(),
                 Tables\Columns\TextColumn::make('descripcion')
                ->sortable()
                ->label('Descripcion')
                ->searchable(),
                 Tables\Columns\TextColumn::make('estado')
                ->sortable()
                ->label('Estado')
                ->searchable(),
            ])
            ->filters([
                //
                SelectFilter::make('estado')
                ->options([
                    'activo' => 'Activo',
                    'mantenimiento' => 'Mantenimiento',
                    'inactivo' => 'Inactivo',
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
            'index' => Pages\ListEspacios::route('/'),
            'create' => Pages\CreateEspacio::route('/create'),
            'edit' => Pages\EditEspacio::route('/{record}/edit'),
        ];
    }
}

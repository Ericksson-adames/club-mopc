<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportesResource\Pages;
use App\Filament\Resources\ReportesResource\RelationManagers;
use App\Models\Reportes;
use App\Models\reservas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReportesResource extends Resource
{
    protected static ?string $model = reservas::class;
    protected static ?string $navigationGroup = 'Gestión';
    protected static ?int $navigationShort = 4;
    protected static ?string $navigationLabel = 'Reportes';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                  Tables\Columns\TextColumn::make('prefijo')
                ->toggleable()
                ->searchable()
                ->label('ID'),
                Tables\Columns\TextColumn::make('espacio.nombre')
                ->toggleable()
                ->searchable()
                ->label('Espacio'),
                Tables\Columns\TextColumn::make('usuario.nombre')
                ->toggleable()
                ->searchable()
                ->label('Usuario'),
                 Tables\Columns\TextColumn::make('solicitante.nombre')
                ->toggleable()
                ->searchable()
                ->label('Solicitante'),
                 Tables\Columns\TextColumn::make('horario.fecha')
                ->toggleable()
                ->searchable()
                ->label('Fecha'),
                 Tables\Columns\BadgeColumn::make('estado')
                ->toggleable()
                ->searchable()
                ->colors([
                    'warning' => 'pendiente',
                    'success' => 'aprobado',
                    'danger' => 'rechazado',
                    'gray' => 'cancelado', 
                ])
                ->label('Estado'),
                 Tables\Columns\TextColumn::make('created_at')
                ->toggleable()
                ->searchable()
                ->label('Fecha de Creación'),

            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListReportes::route('/'),
            'create' => Pages\CreateReportes::route('/create'),
            //'edit' => Pages\EditReportes::route('/{record}/edit'),
        ];
    }
}

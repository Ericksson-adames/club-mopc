<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PagosResource\Pages;
use App\Filament\Resources\PagosResource\RelationManagers;
use App\Models\Pagos;
use App\Models\pagos as ModelsPagos;
use App\Models\reservas;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
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
     protected static ?string $navigationGroup = 'Comercio';
     protected static ?int $navigationShort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->orderBy('id','desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('monto')
                ->numeric()
                ->label('Monto a pagar')
                ->prefix('$')
                ->rules(['numeric', 'min:0'])
                ->required(),
                 Forms\Components\TextInput::make('codigo_pago')
                ->required()
                ->label('Codigo de pago')
                ->live()
                //validando que el codigo de pago exista o sea correcto en la tabla reserva
                /*->rules([
                  'required',
                  'exists:reservas,codigo_pago',
                ])
                ->validationMessages([
                   'codigo_pago.exists' => 'El código ingresado no es válido.',
                ])
                
                /*->rules([
                    fn (string $state): bool => reservas::where('codigo_pago', $state)->exists()
                ])
                ->validationMessages([
                    'codigo_pago' => 'El código ingresado no es válido.',
                ])*/

                // funcion para validar las condiciones del codigo de pago introducido por el usuario
                ->afterStateUpdated(function($state, callable $set){
                    $reserva = reservas::where('codigo_pago', $state)->first();
                    if($reserva){
                        $set('id_reserva', $reserva->id);
                        $set('prefijo', $reserva->prefijo);
                    }else{
                        $set('id_reserva', null);
                        $set('prefijo', 'Codigo Invalido');
                    }
                }),

                Forms\Components\TextInput::make('prefijo')
                ->label('ID reserva')
                ->live()
                ->disabled()
                ->dehydrated(false),
                 Forms\Components\Hidden::make('id_reserva')
                ->required(),
                 Forms\Components\Select::make('metodo_pago')
                 ->label('Tipo de pago')
                 ->options([
                    'efectivo' => 'Efectivo',
                    'tarjeta' => 'Tarjeta',
                    'transferencia' => 'Transferencia',
                 ])
                ->required(),
                 Forms\Components\Select::make('estado')
                 ->visibleOn('edit')
                 ->options([
                    'pago' => 'Pago',
                    'reembolso' => 'Reembolso',
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
                 Tables\Columns\TextColumn::make('created_at')
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
                 Tables\Columns\BadgeColumn::make('estado')
                ->searchable()
                ->colors([
                    'success' => 'pago',
                    'danger' => 'reembolso',
                ])
                ->sortable()
                ->label('Estado')
            ])
            ->filters([
                //
                /*SelectFilter::make('estado')
                ->options([
                    'pago' => 'Pago',
                    'reembolso' => 'Reembolso',
                ])*/
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPagos::route('/'),
            'create' => Pages\CreatePagos::route('/create'),
            'edit' => Pages\EditPagos::route('/{record}/edit'),
        ];
    }
}

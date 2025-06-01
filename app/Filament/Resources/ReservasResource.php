<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservasResource\Pages;
use App\Filament\Resources\ReservasResource\RelationManagers;
use App\Models\espacio;
use App\Models\Reservas;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReservasResource extends Resource
{
    protected static ?string $model = Reservas::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function form(Form $form): Form
      {
        return $form->schema([
        Wizard::make([

            // Paso 1: Espacio
            Step::make('Espacio')
            ->schema([
                Card::make('Espacio a reservar')->schema([
                Select::make('espacio')
                ->relationship('espacio', 'nombre')
                ])
            ]),

            // Paso 2: Solicitante
            Step::make('Solicitante')
                ->schema([
                    Card::make('Datos Personal')->schema([
                        TextInput::make('nombre'),
                        TextInput::make('apellido'),
                        TextInput::make('numero_telefono')->tel(),
                        TextInput::make('correo')->email(),
                    ])->columns(2),
                    Card::make('Imformacion de la empresa')->schema([
                        TextInput::make('empresa'),
                        TextInput::make('departamento'),
                        TextInput::make('extesion'),
                        TextInput::make('telefono_empresa')->tel(),
                    ])->columns(2),
                    Card::make('Invitados')->schema([
                        TextInput::make('numero_invitado')->numeric(),
                    ])->columns(2),
                ]),

            // Paso 3: Horario
            Step::make('Horario')
                ->schema([
                    Card::make()->schema([
                        DatePicker::make('fecha'),
                        TimePicker::make('hora_inicio'),
                        TimePicker::make('hora_finalizar'),
                    ])->columns(2),
                ]),

            // Paso 4: Adicional
            Step::make('Adicional')
                ->schema([
                    Card::make()->schema([
                        Select::make('utilidades')
                        ->options([
                            'sillas'=> 'Sillas',
                            'mesas'=> 'Mesas',
                            'ambos' => 'Ambos',
                            'ninguno'=> 'Ninguno',
                        ])
                        ->reactive()
                        ->live()
                         //funcion para calcular el total de utilidades
                        ->afterStateUpdated(function ($state, callable $set){
                            if($state === 'sillas'){
                                $set('total_utilidades', 0);
                                $set('mesas', 0);
                            }elseif($state === 'mesas'){
                                $set('total_utilidades', 0);
                                $set('sillas', 0);
                            }elseif($state === 'ambos'){
                                 $set('sillas', 0);
                                $set('mesas', 0);
                                $set('total_utilidades', 0);
                            }elseif($state === 'ninguno') {
                                $set('total_utilidades', 0);
                                $set('sillas', 0);
                                $set('mesas', 0);
                            }
                        }),
                        TextInput::make('sillas')
                        //hace visible el campo segun la opcion seleccionada en utilidades
                        ->visible(fn($get) => in_array($get('utilidades'), ['sillas' , 'ambos']) )
                        ->live()
                        //funcion para calcular el total de utilidades
                        ->afterStateUpdated(function($state, callable $set, callable $get) {
                            $sillas = $state ?? 0;
                            $mesas = $get ('mesas') ?? 0;
                            $set('total_utilidades', $sillas + $mesas);
                        })
                        ->numeric(),
                        TextInput::make('mesas')
                        //hace visible el campo segun la opcion seleccionada en utilidades
                        ->visible(fn($get) => in_array($get('utilidades'), ['mesas' , 'ambos']) )
                          ->live()
                           //funcion para calcular el total de utilidades
                        ->afterStateUpdated(function($state, callable $set, callable $get) {
                            $mesas = $state ?? 0;
                            $sillas = $get ('sillas') ?? 0;
                            $set('total_utilidades', $sillas + $mesas);
                        })
                        ->numeric(),
                        TextInput::make('total_utilidades')
                        ->readOnly()
                        ->dehydrated(true)
                        ->live()
                        ->numeric(),
                    ])->columns(2),
                ]),

               // Paso 5: Adicional
              Step::make('Carta')
                ->schema([
                    Card::make()->schema([
                        TextInput::make('nombre_pdf'),
                    ]),
                ]),    

        ])->columnSpanFull(),
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
                    'pendiente' => 'warning',
                    'aprobado' => 'success',
                    'rechazado' => 'danger',
                ])
                ->label('Estado'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListReservas::route('/'),
            'create' => Pages\CreateReservas::route('/create'),
            'edit' => Pages\EditReservas::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservasResource\Pages;
use App\Filament\Resources\ReservasResource\RelationManagers;
use App\Models\espacio;
use App\Models\Reservas;
use Closure;
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
     protected static ?string $navigationGroup = 'Comercio';
     protected static ?int $navigationShort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
      // funcion para organizar de forma desendente el registro de un nuevo usuario
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
        ->orderBy('id','desc');
    }

    public static function form(Form $form): Form
      {
        return $form->schema([
        Wizard::make([

            // Paso 1: Espacio
            Step::make('Espacio')
            ->schema([
                Card::make('Espacio a reservar')->schema([
                Select::make('espacio')
                ->relationship('espacio', 'nombre', fn($query) => $query->where('estado', 'activo'))
                ->required()
                ]),
            
        Card::make(' Estado de reservar')->visibleOn('edit')
        ->schema([
            Select::make('estado')
            ->options([
                //'pendiente' => 'Pendiente',
                //'aprobado' => 'Aprobado',
                'rechazado' => 'Rechazado',
                'cancelado' => 'Cancelado',
            ])
            ->label('Estado')
            ->visibleOn('edit')
            ->required()
        ]),
        ])->visible(fn ()=>true),

            // Paso 2: Solicitante
            Step::make('Solicitante')
                ->schema([
                    Card::make('Datos Personal')
                    ->schema([
                        TextInput::make('nombre')
                        ->required()
                         ->placeholder('Nombre del solicitante')
                        ->label('Nombre'),
                        TextInput::make('apellido')
                         ->required()
                          ->placeholder('Apellidos del solicitante')
                        ->label('Apellidos'),
                        TextInput::make('numero_telefono')
                        ->tel()
                        ->numeric()
                        ->maxlength(10)
                        ->minlength(10)
                        ->placeholder('Sin guiones (-). Ej: 8094986864')
                         ->required()
                        ->label('Telefono'),
                        TextInput::make('correo')
                        ->email()
                         ->required()
                          ->placeholder('Email del solicitante')
                        ->label('Correo'),
                    ])->columns(2),

                    Select::make('tiene_empresa')
                    ->label('¿Pertenece a una empresa?')
                    ->required()
                    ->options([
                        'si' => 'Si',
                        'no' => 'No',
                    ])
                    ->visibleOn('create')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                     if ($state === 'no') {
                     $set('empresa', 'no');
                     $set('departamento', 'no');
                     $set('telefono_empresa', '0000000000');
                     $set('extesion', '0000');
                     }
                  }),

                    Card::make('Imformacion de la empresa')->schema([
                        TextInput::make('empresa')
                         ->required()
                         ->dehydrated()
                          ->placeholder('Nombre de la empresa perteneciente')
                        ->label('Nombre de la empresa'),
                        TextInput::make('departamento')
                         ->required()
                         ->dehydrated()
                          ->placeholder('Departamento al que pertenece')
                        ->label('Departamento'),
                        TextInput::make('telefono_empresa')
                        ->tel()
                        ->dehydrated()
                         ->placeholder('Sin guiones (-). Ej: 8094986864')
                        ->numeric()
                        ->maxlength(10)
                        ->minlength(10)
                         ->required()
                        ->label('Telefono de la empresa'),
                        TextInput::make('extesion')
                         ->required()
                         ->numeric()
                         ->dehydrated()
                          ->placeholder('Extension del departamento')
                         ->maxlength(4)
                         ->minlength(4)
                        ->label('Extension'),
                    ])
                    ->columns(2)
                    ->visible(fn ($get) => $get('tiene_empresa') === 'si'),

                    Card::make('Invitados')->schema([
                        TextInput::make('numero_invitado')
                        ->numeric()
                         ->placeholder('Numero de invitados')
                        ->label('Numero de invitados')
                        ->required(),
                         TextInput::make('tipo_actividad')
                         ->placeholder('Que tipo de actividad se realizará')
                        ->label('Tipo de actividad')
                        ->required(),
                    ])
                     ->columns(2),
                ])->visible(fn ()=>true),

            // Paso 3: Horario
            Step::make('Horario')
                ->schema([
                    Card::make()->schema([
                        DatePicker::make('fecha')
                         ->required()
                          ->placeholder('Fecha de la reserva')
                        ->label('Fecha de la reunion'),
                        TimePicker::make('hora_inicio')
                         ->required()
                        ->label('Hora de inicio'),
                        TimePicker::make('hora_finalizar')
                         ->required()
                        ->label('Hora de finalizar'),
                    ])->columns(2),
                ])->visible(fn ()=>true),

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
                        })
                         ->required()
                        ->label('Selecionar utilidades'),
                        TextInput::make('sillas')
                        //hace visible el campo segun la opcion seleccionada en utilidades
                        ->visible(fn($get) => in_array($get('utilidades'), ['sillas' , 'ambos']) )
                        ->live()
                         ->placeholder('Cantidad de sillas')
                        //funcion para calcular el total de utilidades
                        ->afterStateUpdated(function($state, callable $set, callable $get) {
                            $sillas = $state ?? 0;
                            $mesas = $get ('mesas') ?? 0;
                            $set('total_utilidades', $sillas + $mesas);
                        })
                        ->numeric()
                         ->required()
                        ->label('Numero de sillas'),

                        TextInput::make('mesas')
                        //hace visible el campo segun la opcion seleccionada en utilidades
                        ->visible(fn($get) => in_array($get('utilidades'), ['mesas' , 'ambos']) )
                          ->live()
                           ->placeholder('Cantidad de mesas')
                           //funcion para calcular el total de utilidades
                        ->afterStateUpdated(function($state, callable $set, callable $get) {
                            $mesas = $state ?? 0;
                            $sillas = $get ('sillas') ?? 0;
                            $set('total_utilidades', $sillas + $mesas);
                        })
                        ->numeric()
                         ->required()
                        ->label('Numero de mesas'),

                        TextInput::make('total_utilidades')
                        ->readOnly()
                        ->dehydrated(true)
                        ->live()
                        ->numeric()
                        ->label('Total de utilidades'),
                    ])->columns(2),
                ])->visible(fn ()=>true),

               /* Paso 5: crta
              Step::make('Carta')
                ->schema([
                    Card::make()->schema([
                        TextInput::make('nombre_pdf'),
                    ]),
                ]), */   

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
                ->url(fn ($record) => route('filament.admin.resources.reservas.view', ['record' => $record]))
                ->openUrlInNewTab(false)
                ->searchable()
                ->label('ID'),
                Tables\Columns\TextColumn::make('espacio.nombre')
                ->toggleable()
                ->url(fn ($record) => route('filament.admin.resources.reservas.view', ['record' => $record]))
                ->openUrlInNewTab(false)
                ->searchable()
                ->label('Espacio'),
                Tables\Columns\TextColumn::make('usuario.nombre')
                ->toggleable()
                ->url(fn ($record) => route('filament.admin.resources.reservas.view', ['record' => $record]))
                ->openUrlInNewTab(false)
                ->searchable()
                ->label('Usuario'),
                 Tables\Columns\TextColumn::make('solicitante.nombre')
                ->toggleable()
                ->url(fn ($record) => route('filament.admin.resources.reservas.view', ['record' => $record]))
                ->openUrlInNewTab(false)
                ->searchable()
                ->label('Solicitante'),
                 Tables\Columns\TextColumn::make('horario.fecha')
                ->toggleable()
                ->url(fn ($record) => route('filament.admin.resources.reservas.view', ['record' => $record]))
                ->openUrlInNewTab(false)
                ->searchable()
                ->label('Fecha'),
                 Tables\Columns\BadgeColumn::make('estado')
                ->toggleable()
                ->url(fn ($record) => route('filament.admin.resources.reservas.view', ['record' => $record]))
                ->openUrlInNewTab(false)
                ->searchable()
                ->colors([
                    'warning' => 'pendiente',
                    'success' => 'aprobado',
                    'danger' => 'rechazado',
                    'gray' => 'cancelado', 
                ])
                ->label('Estado'),
            ])
            ->filters([
                //
            ])
            ->actions([
               Tables\Actions\EditAction::make()
               ->url(fn ($record) => route('filament.admin.resources.reservas.edit', ['record' => $record])),
                //Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListReservas::route('/'),
            'create' => Pages\CreateReservas::route('/create'),
            'edit' => Pages\EditReservas::route('/{record}/edit'),
            'view' => Pages\ViewReserva::route('/{record}'),
        ];
    }
}

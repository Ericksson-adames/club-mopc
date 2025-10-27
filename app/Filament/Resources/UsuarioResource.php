<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsuarioResource\Pages;
use App\Filament\Resources\UsuarioResource\RelationManagers;
use App\Models\Usuario;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UsuarioResource extends Resource
{
    protected static ?string $model = Usuario::class;

    protected static ?string $navigationGroup = 'Gestión';
    protected static ?int $navigationShort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    // funcion para organizar de forma desendente el registro de un nuevo usuario
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
                Forms\Components\TextInput::make ('nombre')
                         // funcion para que esten limpios los input cuando se cree un usuario nuevo 
                ->required(fn (string $operation): bool => $operation === 'create' ),
               // ->orderBy('nombre', 'desc'),
                Forms\Components\TextInput::make ('apellidos')
                         // funcion para que esten limpios los input cuando se cree un usuario nuevo 
                ->required(fn (string $operation): bool => $operation === 'create' ),
                Forms\Components\TextInput::make ('email')
                ->email()
                         // funcion para que esten limpios los input cuando se cree un usuario nuevo 
                ->required(fn (string $operation): bool => $operation === 'create' ),
                Forms\Components\TextInput::make ('password')
                       // funcion para que esten limpios los input cuando se cree un usuario nuevo 
                ->required(fn (string $operation): bool => $operation === 'create' )
                ->password()
                // funcion para cambiar el formato de la contraseña
                ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                // funcion para controlar el formato de la contraseña y si debe ser enviado para guardar
                ->dehydrated(fn ($state)=> filled($state))
                // funcion para que esten limpios el input cuando se valla a editar un usuario, si el campo queda vacio, no se edita la contraseña
                ->placeholder(fn (string $operation) => $operation === 'edit' ? 'dejar vacio para mantener contraseña actual' : null)
                ->autocomplete('new-password'),
                Forms\Components\Select::make ('id_rol')
                ->options([
                    '1' => 'Administrador',
                    '2' => 'Usuario',
                    '3' => 'Invitado',
                ])
                ->required(),
                Forms\Components\Select::make ('estado')
                ->options([
                    'activo' =>'Activo',
                    'inactivo' =>'Inactivo'
                ])
                //funcion para que el campo solo sea visible cuando se valla a editar un usuario
                ->visible(fn(string $operation) =>$operation === 'edit')
                ->required(),
                Forms\Components\DatePicker::make ('fecha_de_nacimiento')
                         // funcion para que esten limpios los input cuando se cree un usuario nuevo 
                ->required(fn (string $operation): bool => $operation === 'create' ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                 Tables\Columns\TextColumn::make('prefijo')
                 ->label('ID')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('nombre')
                ->label('Nombre')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('apellidos')
                ->label('Apellidos')
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('email')
                ->label('Correo')
                 ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('rol.nombre')
                ->label('Rol')
                 ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('fecha_de_nacimiento')
                ->label('Fecha de nacimiento')
                ->dateTime()
                 ->sortable()
                ->searchable(),
                Tables\Columns\BadgeColumn::make('estado')
                ->label('Estado')
                 ->sortable()
                 ->colors([
                    'danger' => 'inactivo',
                    'success' => 'activo'
                 ])
                ->searchable(),
                 Tables\Columns\TextColumn::make('created_at')
                 ->label('Creado')
                 ->dateTime()
                 ->sortable()
                ->searchable(),
                 Tables\Columns\TextColumn::make('update_at')
                 ->label('Actualizado')
                 ->dateTime()
                 ->sortable()
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                SelectFilter::make ('estado')
                ->label('Estado')
                ->options([
                    'activo' =>'Activo',
                    'inactivo' =>'Inactivo'
                ])
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
            'index' => Pages\ListUsuarios::route('/'),
            'create' => Pages\CreateUsuario::route('/create'),
            'edit' => Pages\EditUsuario::route('/{record}/edit'),
        ];
    }
}

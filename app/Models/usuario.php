<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;

// clase para indicar que este sera mi usuario de autenticacion
class usuario extends Authenticatable implements FilamentUser
{
    use Notifiable;

     // indico a filament que esta es mi tabla en la base de datos 
    protected $table = 'usuario';

     // indico a filament que esta es mi llave primaria en la base de datos 
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
        'fecha_de_nacimiento',
        'id_rol',
        'estado',
    ];

    protected $hidden = [
        'password',
    ];

    //es un accessor de Eloquent en Laravel que crea una propiedad virtual llamada name.
    public function getNameAttribute(): string
    {
        return $this->nombre ?? 'Sin nombre';
    }

    //es para específica a Filament y cumple el propósito de mostrar el nombre del usuario en la interfaz del panel.
    public function getFilamentName(): string
    {
        return $this->nombre ?? 'Sin nombre';
    }
    
    //es para cuando tu modelo de usuario usa un campo de contraseña con un nombre distinto al predeterminado de Laravel, que es password 
    //(lo tenia diferente, pero lo cambie y lo deje como quiera)
    public function getAuthPassword()
    {
        return $this->password;
    }
    
    //es requerida por Filament cuando tu modelo implementa la interfaz (Filament\Models\Contracts\FilamentUser)
    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }

    //relacion con la tabla rol
    public function rol(){
        return $this->belongsTo(rol::class, 'id_rol');
    }

    //relacion con la tabla reservas
    public function reservas(){
        return $this->hasMany(reservas::class, 'id_usuario');
    }

    //relacion con la tabla historia_reservas
    public function historia_reservas(){
        return $this->hasMany(historial_reservas::class, 'id_usuario');
    }
}

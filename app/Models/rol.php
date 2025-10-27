<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class rol extends Model
{
    //
    protected $table = 'rol';

    protected $primaryKey = 'id';

    public $timestamps = false;

    //relacion con la tabla usuario
    public function usuario() {
        return $this->hasMany(usuario::class, 'id_rol');
    }

    //relacion con la tabla permisos
    public function permisos() {
        return $this->hasMany(permisos::class, 'id_rol');
    }
}

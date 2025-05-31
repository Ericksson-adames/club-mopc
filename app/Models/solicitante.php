<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class solicitante extends Model
{
    //
    protected $table = 'solicitante';
    protected $primaryKey = 'id_solicitante';
    public $timestamps = false;

 protected $fillable = [
    'nombre',
    'apellido',
    'numero_telefono',
    'correo',
    'empresa',
    'departamento',
    'extesion',
    'telefono_empresa',
    'numero_invitado'
 ];
    //relacion con la tabla reservas
    public function reservas(){
    return $this->hasMany(reservas::class, 'id_solicitante');
}

}

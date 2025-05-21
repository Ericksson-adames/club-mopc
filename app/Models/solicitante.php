<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class solicitante extends Model
{
    //
    protected $table = 'solicitante';
    protected $primaryKey = 'id_solicitante';
    public $timestamps = false;


    //relacion con la tabla reservas
    public function reservas(){
    return $this->hasMany(reservas::class, 'id_solicitante');
}

//relacion con la tabla invitado
public function invitados(){
    return $this->hasMany(invitado::class, 'id_solicitante');
}

}

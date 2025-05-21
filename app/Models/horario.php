<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class horario extends Model
{
    //
    protected $table = 'horario';
    protected $primaryKey = 'id';
    public $timestamps = false;

    //relacion con la tabla reservas
 public function reservas(){
    return $this->hasMany(Reservas::class, 'id_horario');
}

}

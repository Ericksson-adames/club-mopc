<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class horario extends Model
{
    //
    protected $table = 'horario';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'hora_inicio',
        'hora_finalizar',
    ];

    //relacion con la tabla reservas
 /*public function reservas(){
    return $this->hasMany(Reservas::class, 'id_horario');
}*/
//prueba
public function reserva()
{
    return $this->hasOne(reservas::class, 'id_horario');
}

}

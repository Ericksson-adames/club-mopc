<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class historial_reservas extends Model
{
    //
    protected $table = 'historial_reservas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    // reserva es la tabla que se relaciona, id_reserva es la columna que se relaciona.
    //relacion con la tabla reserva
    public function reservas(){
      return $this->belongsTo(reservas::class, 'id_reserva');
    }

    //relacion con la tabla usuarios
    public function usuarios(){
        return $this->belongsTo(usuario::class, 'id_usuario');
    }
}

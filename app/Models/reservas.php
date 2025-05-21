<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reservas extends Model
{
    //

 protected $table = 'reservas';

 protected $primaryKey = 'id';

 public $timestamps = false;

 //relacion con la hstorial_reservas
 public function historial_reservas(){
    return $this->hasMany(historial_reservas::class, 'id_reserva');
 }

 //relacion con la tabla pagos
 public function pagos(){
    return $this->hasMany(pagos::class, 'id_reserva');
 }

 //relacion con la tabla usuarios
 public function usuarios(){
    return $this->belongsTo (usuario::class, 'id_usuario');
 }

 //relacion con la tabla espaco
 public function espaco(){
    return $this->belongsTo(espacio::class, 'id_espacio');
 }

 //relacion con la tabla adicional
 public function adicionales(){
    return $this->belongsTo(adicional::class, 'id_adicional');
 }

 //relacion con la tabla horario
 public function horario(){
    return $this->belongsTo(horario::class, 'id_horario');
 }

 //relacion con la tabla solicitante
 public function solicitante(){
    return $this->belongsTo(solicitante::class, 'id_solicitante');
 }

 //relacion con la tabla carta
 public function carta(){
    return $this->belongsTo(carta::class, 'id_carta');
 }
}

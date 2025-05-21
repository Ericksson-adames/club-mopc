<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class invitado extends Model
{
    //
    protected $table = 'invitados';
    protected $primaryKey = 'id';
    public $timestamps = false;

    //relacion con la tabla solicitante
    public function solicitante(){
        return $this->belongsTo(solicitante::class, 'id_solicitante');
    }

}

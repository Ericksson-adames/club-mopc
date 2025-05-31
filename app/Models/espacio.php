<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class espacio extends Model
{
    //
    protected $table = 'espacio';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'capacidad',
        'ubicacion',
        'estado',
    ];

    //relacion con la tabla reservas
 public function reservas(){
    return $this->hasMany(Reservas::class, 'id_espacio');
}
}

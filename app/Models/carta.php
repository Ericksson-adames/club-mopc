<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class carta extends Model
{
    //
    protected $table = 'carta';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'nombre_pdf',
    ];


    //relacion con la tabla reservas
 public function reservas(){
    return $this->hasMany(Reservas::class, 'id_carta');
}
}

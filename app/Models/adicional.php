<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class adicional extends Model
{
    //
    protected $table = 'adicional';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'utilidades',
        'sillas',
        'mesas',
        'total_utilidades'
    ];

    //relacion con la tabla reservas
 public function reservas(){
    return $this->hasMany(Reservas::class, 'id_adicional');
}
}

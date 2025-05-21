<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class permisos extends Model
{
    //
    protected $table = 'permisos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    //relacion con el modelo rol
    public function rol(){
        return $this->belongsTo(rol::class, 'id_rol');
    }
}

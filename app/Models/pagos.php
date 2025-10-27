<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pagos extends Model
{
    //
    protected $table = 'pagos';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'monto',
        'codigo_pago',
        'metodo_pago',
        'estado',
        'id_reserva',
    ];

    //relacion con la tabla de reservas
    public function reservas(){
        return $this->belongsTo(reservas::class, 'id');
    }
}

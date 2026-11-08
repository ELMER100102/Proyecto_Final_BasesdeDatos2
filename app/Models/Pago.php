<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'Pago';
    protected $primaryKey = 'PagoId';
    public $timestamps = false;

    protected $fillable = ['ReservaId', 'Tipo', 'Metodo', 'Monto'];
    protected $casts = ['Monto' => 'decimal:2', 'Fecha' => 'datetime'];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'ReservaId', 'ReservaId');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'Cargo';
    protected $primaryKey = 'CargoId';
    public $timestamps = false;

    protected $fillable = ['ReservaId', 'ServicioId', 'Cantidad', 'PrecioUnitario'];
    protected $casts = ['PrecioUnitario' => 'decimal:2', 'Fecha' => 'datetime'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'ServicioId', 'ServicioId');
    }

    public function getSubtotalAttribute(): float
    {
        return $this->Cantidad * $this->PrecioUnitario;
    }
}

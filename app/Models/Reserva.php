<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Las reservas se crean, cancelan y cierran con HotelService (procedimientos).
 * Este modelo se usa para listarlas y mostrarlas.
 */
class Reserva extends Model
{
    protected $table = 'Reserva';
    protected $primaryKey = 'ReservaId';
    public $timestamps = false;

    protected $casts = [
        'FechaEntrada' => 'date',
        'FechaSalida'  => 'date',
        'FechaReserva' => 'datetime',
        'Total'        => 'decimal:2',
    ];

    public function huesped()
    {
        return $this->belongsTo(Huesped::class, 'HuespedId', 'PersonaId');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleReserva::class, 'ReservaId', 'ReservaId');
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class, 'ReservaId', 'ReservaId');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'ReservaId', 'ReservaId');
    }

    public function getPagadoAttribute(): float
    {
        return (float) $this->pagos->sum(fn ($p) => $p->Tipo === 'REEMBOLSO' ? -$p->Monto : $p->Monto);
    }
}

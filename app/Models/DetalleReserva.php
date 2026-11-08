<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Llave compuesta (ReservaId, HabitacionId): Eloquent no la soporta para
 * guardar, así que este modelo es solo de lectura. Lo llena sp_CrearReserva.
 */
class DetalleReserva extends Model
{
    protected $table = 'DetalleReserva';
    protected $primaryKey = 'ReservaId';
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = ['PrecioNoche' => 'decimal:2', 'Subtotal' => 'decimal:2'];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'ReservaId', 'ReservaId');
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class, 'HabitacionId', 'HabitacionId');
    }
}

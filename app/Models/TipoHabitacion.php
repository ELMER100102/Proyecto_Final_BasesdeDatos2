<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoHabitacion extends Model
{
    protected $table = 'TipoHabitacion';
    protected $primaryKey = 'TipoHabitacionId';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Descripcion', 'Capacidad', 'PrecioNoche', 'FotoUrl', 'Activo'];
    protected $casts = ['PrecioNoche' => 'decimal:2', 'Activo' => 'boolean'];

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'TipoHabitacionId', 'TipoHabitacionId');
    }
}

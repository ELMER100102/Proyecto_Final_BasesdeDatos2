<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table = 'Habitacion';
    protected $primaryKey = 'HabitacionId';
    public $timestamps = false;

    protected $fillable = ['TipoHabitacionId', 'Numero', 'Piso', 'Estado'];

    public function tipo()
    {
        return $this->belongsTo(TipoHabitacion::class, 'TipoHabitacionId', 'TipoHabitacionId');
    }
}

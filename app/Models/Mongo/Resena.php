<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class Resena extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'resenas';
    public $timestamps = false;

    protected $fillable = [
        'reservaId', 'huespedId', 'huesped', 'tipoHabitacion',
        'calificacion', 'comentario', 'fotos', 'respuestaHotel', 'fecha',
    ];

    protected $casts = ['fecha' => 'datetime'];
}
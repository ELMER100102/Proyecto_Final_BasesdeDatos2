<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

/** Historial de respaldos hechos desde el panel (colección "respaldos"). */
class Respaldo extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'respaldos';
    public $timestamps = false;

    protected $fillable = ['tipo', 'archivo', 'ruta', 'bytes', 'colecciones', 'usuario', 'fecha', 'estado', 'error'];

    protected $casts = ['fecha' => 'datetime'];
}

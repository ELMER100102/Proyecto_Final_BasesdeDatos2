<?php

namespace App\Models\Mongo;

use MongoDB\Laravel\Eloquent\Model;

class Mensaje extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'mensajes';
    public $timestamps = false;

    protected $fillable = ['nombre', 'correo', 'telefono', 'asunto', 'mensaje', 'respondido', 'fecha'];

    protected $casts = ['respondido' => 'boolean', 'fecha' => 'datetime'];
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $table = 'Servicio';
    protected $primaryKey = 'ServicioId';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Precio', 'Activo'];
    protected $casts = ['Precio' => 'decimal:2', 'Activo' => 'boolean'];
}

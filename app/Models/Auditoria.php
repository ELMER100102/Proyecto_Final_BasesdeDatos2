<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Historial que escriben los triggers. Solo lectura desde la aplicación. */
class Auditoria extends Model
{
    protected $table = 'Auditoria';
    protected $primaryKey = 'AuditoriaId';
    public $timestamps = false;

    protected $casts = ['Fecha' => 'datetime'];

    /** El JSON de la columna Datos convertido en arreglo. */
    public function getDetalleAttribute(): array
    {
        return json_decode($this->Datos ?? '', true) ?: [];
    }
}

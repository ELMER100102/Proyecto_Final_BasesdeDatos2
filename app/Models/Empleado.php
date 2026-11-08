<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'Empleado';
    protected $primaryKey = 'PersonaId';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['PersonaId', 'Puesto', 'Salario'];
    protected $casts = ['Salario' => 'decimal:2'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'PersonaId', 'PersonaId');
    }
}

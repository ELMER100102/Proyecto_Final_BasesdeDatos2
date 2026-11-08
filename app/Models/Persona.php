<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Cedula y Telefono están encriptados en SQL Server.
 * Para crear una persona use HotelService::registrarPersona()
 * y para verla desencriptada HotelService::obtenerPersona().
 */
class Persona extends Model
{
    protected $table = 'Persona';
    protected $primaryKey = 'PersonaId';
    public $timestamps = false;

    protected $fillable = ['Nombre', 'Apellidos', 'Correo'];
    protected $hidden = ['Cedula', 'Telefono'];
    protected $casts = ['FechaRegistro' => 'datetime'];

    public function huesped()
    {
        return $this->hasOne(Huesped::class, 'PersonaId', 'PersonaId');
    }

    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'PersonaId', 'PersonaId');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'PersonaId', 'PersonaId');
    }

    public function getNombreCompletoAttribute(): string
    {
        return "{$this->Nombre} {$this->Apellidos}";
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Usuario para el login de Laravel (config/auth.php apunta a este modelo).
 * El correo está en Persona y la contraseña en ContrasenaHash (bcrypt).
 */
class Usuario extends Authenticatable
{
    protected $table = 'Usuario';
    protected $primaryKey = 'UsuarioId';
    public $timestamps = false;

    protected $fillable = ['PersonaId', 'Rol', 'ContrasenaHash', 'Activo'];
    protected $hidden = ['ContrasenaHash'];
    protected $casts = ['Activo' => 'boolean'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'PersonaId', 'PersonaId');
    }

    // Laravel busca la contraseña en esta columna
    public function getAuthPasswordName()
    {
        return 'ContrasenaHash';
    }

    public function getAuthPassword()
    {
        return $this->ContrasenaHash;
    }

    // La tabla no tiene columna remember_token
    public function getRememberTokenName()
    {
        return null;
    }

    public function esAdmin(): bool
    {
        return $this->Rol === 'ADMINISTRADOR';
    }

    public function esEmpleado(): bool
    {
        return in_array($this->Rol, ['ADMINISTRADOR', 'RECEPCIONISTA']);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Huesped extends Model
{
    protected $table = 'Huesped';
    protected $primaryKey = 'PersonaId';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['PersonaId', 'Nacionalidad'];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'PersonaId', 'PersonaId');
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'HuespedId', 'PersonaId');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'Hotel';
    protected $primaryKey = 'HotelId';
    public $timestamps = false;

    protected $fillable = [
        'Nombre', 'Descripcion', 'LogoUrl', 'Direccion', 'Latitud', 'Longitud',
        'Telefono', 'Correo', 'PorcentajeImpuesto',
    ];

    public function imagenes()
    {
        return $this->hasMany(ImagenSitio::class, 'HotelId', 'HotelId');
    }
}

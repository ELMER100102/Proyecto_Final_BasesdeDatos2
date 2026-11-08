<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagenSitio extends Model
{
    protected $table = 'ImagenSitio';
    protected $primaryKey = 'ImagenId';
    public $timestamps = false;

    protected $fillable = ['HotelId', 'Tipo', 'Titulo', 'ImagenUrl', 'Orden', 'Activo'];
    protected $casts = ['Activo' => 'boolean'];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'HotelId', 'HotelId');
    }
}

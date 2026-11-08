<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\ImagenSitio;
use App\Models\Mongo\Resena;
use App\Models\TipoHabitacion;

class InicioController extends Controller
{
    public function index()
    {
        $imagenes = ImagenSitio::where('Activo', true)->orderBy('Orden')->get();

        return view('web.inicio', [
            'hotel'   => Hotel::first(),
            'slides'  => $imagenes->where('Tipo', 'SLIDE'),
            'galeria' => $imagenes->where('Tipo', 'GALERIA'),
            'tipos'   => TipoHabitacion::where('Activo', true)->orderBy('PrecioNoche')->get(),
            'resenas' => Resena::where('calificacion', '>=', 4)->orderByDesc('fecha')->take(3)->get(),
        ]);
    }
}

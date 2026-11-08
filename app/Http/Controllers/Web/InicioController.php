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
        return view('web.inicio', [
            'hotel'   => Hotel::first(),
            'slides'  => ImagenSitio::where('Tipo', 'SLIDE')->where('Activo', true)->orderBy('Orden')->get(),
            'tipos'   => TipoHabitacion::where('Activo', true)->orderBy('PrecioNoche')->get(),
            'resenas' => Resena::where('calificacion', '>=', 4)->orderByDesc('fecha')->take(3)->get(),
        ]);
    }
}

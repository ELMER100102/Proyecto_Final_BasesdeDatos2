<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mongo\Resena;
use App\Models\TipoHabitacion;

class HabitacionController extends Controller
{
    public function index()
    {
        return view('web.habitaciones.index', [
            'tipos' => TipoHabitacion::where('Activo', true)->orderBy('PrecioNoche')->get(),
        ]);
    }

    public function show(int $id)
    {
        $tipo = TipoHabitacion::where('Activo', true)->findOrFail($id);

        return view('web.habitaciones.show', [
            'tipo'    => $tipo,
            'resenas' => Resena::where('tipoHabitacion', $tipo->Nombre)->orderByDesc('fecha')->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\Mongo\Mensaje;
use App\Models\Reserva;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = now()->toDateString();

        return view('admin.dashboard', [
            'enCurso'       => Reserva::where('Estado', 'EN_CURSO')->count(),
            'llegadasHoy'   => Reserva::whereDate('FechaEntrada', $hoy)->whereIn('Estado', ['PENDIENTE', 'CONFIRMADA'])->count(),
            'habitaciones'  => Habitacion::selectRaw('Estado, COUNT(*) AS total')->groupBy('Estado')->pluck('total', 'Estado'),
            'mensajes'      => Mensaje::where('respondido', false)->count(),
            'ultimas'       => Reserva::with('huesped.persona')->orderByDesc('ReservaId')->take(5)->get(),
        ]);
    }
}

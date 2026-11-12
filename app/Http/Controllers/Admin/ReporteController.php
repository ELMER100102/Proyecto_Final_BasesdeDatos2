<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HotelService;
use Illuminate\Http\Request;


class ReporteController extends Controller
{
    public function index(Request $request, HotelService $hotel)
    {
        $desde = $request->input('desde', now()->subMonths(3)->startOfMonth()->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());

        $filas = collect($hotel->reporteIngresos($desde, $hasta));

        return view('admin.reporte.index', [
            'desde'   => $desde,
            'hasta'   => $hasta,
            'filas'   => $filas,
            'totales' => [
                'Reservas'         => $filas->sum('Reservas'),
                'Noches'           => $filas->sum('Noches'),
                'IngresoHospedaje' => $filas->sum('IngresoHospedaje'),
                'IngresoConsumos'  => $filas->sum('IngresoConsumos'),
            ],
        ]);
    }
}

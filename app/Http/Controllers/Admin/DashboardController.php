<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\Mongo\Mensaje;
use App\Models\Reserva;
use App\Services\CalificacionService;
use App\Services\HotelService;
use Throwable;

class DashboardController extends Controller
{
    public function index(HotelService $hotel, CalificacionService $calificaciones)
    {
        $hoy = now()->toDateString();
        $habitaciones = Habitacion::selectRaw('Estado, COUNT(*) AS total')->groupBy('Estado')->pluck('total', 'Estado');

        return view('admin.dashboard', [
            'enCurso'      => Reserva::where('Estado', 'EN_CURSO')->count(),
            'llegadasHoy'  => Reserva::whereDate('FechaEntrada', $hoy)->whereIn('Estado', ['PENDIENTE', 'CONFIRMADA'])->count(),
            'habitaciones' => $habitaciones,
            'mensajes'     => $this->mensajesPendientes(),
            'ultimas'      => Reserva::with('huesped.persona')->orderByDesc('ReservaId')->take(5)->get(),
            'graficos'     => [
                'ingresos'       => $this->ingresosPorMes($hotel),
                'habitaciones'   => $habitaciones,
                'calificaciones' => $calificaciones->promedios(),
            ],
        ]);
    }

    /** Últimos 6 meses (incluido el actual), con 0 en los meses sin ingresos. */
    private function ingresosPorMes(HotelService $hotel): array
    {
        $inicio = now()->startOfMonth()->subMonths(5);
        $filas  = collect($hotel->reporteIngresos($inicio->toDateString(), now()->toDateString()))->groupBy('Mes');

        $meses = $hospedaje = $consumos = [];
        for ($m = $inicio->copy(); $m <= now(); $m->addMonth()) {
            $clave       = $m->format('Y-m');
            $meses[]     = ucfirst($m->locale('es')->translatedFormat('M Y'));
            $hospedaje[] = (float) ($filas[$clave] ?? collect())->sum('IngresoHospedaje');
            $consumos[]  = (float) ($filas[$clave] ?? collect())->sum('IngresoConsumos');
        }

        return compact('meses', 'hospedaje', 'consumos');
    }

    private function mensajesPendientes(): int
    {
        try {
            return Mensaje::where('respondido', false)->count();
        } catch (Throwable) {
            return 0;
        }
    }
}

<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\HotelService;
use Illuminate\Http\Request;
use RuntimeException;

class ReservaController extends Controller
{
    /** Buscador: sin fechas muestra solo el formulario; con fechas, los tipos disponibles. */
    public function create(Request $request, HotelService $hotel)
    {
        $resultados = null;

        if ($request->filled(['entrada', 'salida'])) {
            $datos = $request->validate([
                'entrada'  => 'required|date|after_or_equal:today',
                'salida'   => 'required|date|after:entrada',
                'personas' => 'required|integer|min:1|max:10',
            ]);
            $resultados = $hotel->disponibilidad($datos['entrada'], $datos['salida'], $datos['personas']);
        }

        return view('web.reservar', ['resultados' => $resultados]);
    }

    /** Crea la reserva con sp_CrearReserva. Solo huéspedes con sesión. */
    public function store(Request $request, HotelService $hotel)
    {
        $datos = $request->validate([
            'tipo'     => 'required|integer',
            'entrada'  => 'required|date|after_or_equal:today',
            'salida'   => 'required|date|after:entrada',
            'personas' => 'required|integer|min:1',
            'adelanto' => 'nullable|numeric|min:0',
            'metodo'   => 'nullable|in:EFECTIVO,TARJETA,SINPE',   // si hay adelanto sin método, el procedimiento lo rechaza
        ]);

        try {
            $reserva = $hotel->crearReserva(
                $request->user()->PersonaId, $datos['tipo'], $datos['entrada'], $datos['salida'],
                $datos['personas'], (float) ($datos['adelanto'] ?? 0), $datos['metodo'] ?? null
            );
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('mis-reservas')
            ->with('ok', "Reserva #{$reserva->ReservaId} creada en la habitación {$reserva->Habitacion}.");
    }
}

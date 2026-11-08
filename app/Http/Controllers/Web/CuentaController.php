<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mongo\Resena;
use App\Models\Reserva;
use App\Services\HotelService;
use Illuminate\Http\Request;
use RuntimeException;

// Controlador para el área de cuenta del huésped (mis reservas, reseñas, etc.)
class CuentaController extends Controller
{
    public function misReservas(Request $request)
    {
        $reservas = Reserva::with(['detalles.habitacion.tipo', 'pagos'])
            ->where('HuespedId', $request->user()->PersonaId)
            ->orderByDesc('FechaEntrada')
            ->get();

        $conResena = Resena::whereIn('reservaId', $reservas->pluck('ReservaId')->all())->pluck('reservaId')->all();

        return view('web.mis-reservas', compact('reservas', 'conResena'));
    }

    public function cancelar(Request $request, int $id, HotelService $hotel)
    {
        $this->reservaPropia($request, $id);

        try {
            $resultado = $hotel->cancelarReserva($id);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $mensaje = $resultado->Reembolso > 0
            ? 'Reserva cancelada. Se le devolverán ₡' . number_format($resultado->Reembolso, 0, ',', '.') . '.'
            : 'Reserva cancelada.';

        return back()->with('ok', $mensaje);
    }

    public function crearResena(Request $request, int $id)
    {
        $reserva = $this->reservaPropia($request, $id);
        $this->validarPuedeResenar($reserva);

        return view('web.resena', compact('reserva'));
    }

    public function guardarResena(Request $request, int $id)
    {
        $reserva = $this->reservaPropia($request, $id);
        $this->validarPuedeResenar($reserva);

        $datos = $request->validate([
            'calificacion' => 'required|integer|min:1|max:5',
            'comentario'   => 'required|string|min:10|max:1000',
        ]);

        Resena::create([
            'reservaId'      => $reserva->ReservaId,
            'huespedId'      => $reserva->HuespedId,
            'huesped'        => $request->user()->persona->nombre_completo,
            'tipoHabitacion' => $reserva->detalles->first()?->habitacion?->tipo?->Nombre,
            'calificacion'   => (int) $datos['calificacion'],
            'comentario'     => $datos['comentario'],
            'fecha'          => now(),
        ]);

        return redirect()->route('mis-reservas')->with('ok', '¡Gracias por su reseña!');
    }

    /* ---------- Ayudantes ---------- */

    private function reservaPropia(Request $request, int $id): Reserva
    {
        return Reserva::with('detalles.habitacion.tipo')
            ->where('HuespedId', $request->user()->PersonaId)
            ->findOrFail($id);
    }

    private function validarPuedeResenar(Reserva $reserva): void
    {
        abort_if($reserva->Estado !== 'FINALIZADA', 403, 'Solo puede reseñar estancias finalizadas.');
        abort_if(Resena::where('reservaId', $reserva->ReservaId)->exists(), 403, 'Ya dejó una reseña para esta reserva.');
    }
}

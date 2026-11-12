<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Huesped;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Models\TipoHabitacion;
use App\Services\HotelService;
use Illuminate\Http\Request;
use RuntimeException;

/** Operación de recepción: reservas, check-in, consumos, check-out y cancelación. */
class ReservaController extends Controller
{
    public function __construct(private HotelService $hotel)
    {
    }

    public function index(Request $request)
    {
        $reservas = Reserva::with(['huesped.persona', 'detalles.habitacion'])
            ->when($request->estado, fn ($q, $estado) => $q->where('Estado', $estado))
            ->orderByDesc('FechaEntrada')
            ->get();

        return view('admin.reservas.index', compact('reservas'));
    }

    public function create()
    {
        return view('admin.reservas.create', [
            'huespedes' => Huesped::with('persona')->get()->sortBy(fn ($h) => $h->persona->nombre_completo),
            'tipos'     => TipoHabitacion::where('Activo', true)->orderBy('PrecioNoche')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'HuespedId'        => 'required|integer|exists:Huesped,PersonaId',
            'TipoHabitacionId' => 'required|integer',
            'FechaEntrada'     => 'required|date|after_or_equal:today',
            'FechaSalida'      => 'required|date|after:FechaEntrada',
            'CantPersonas'     => 'required|integer|min:1',
            'Adelanto'         => 'nullable|numeric|min:0',
            'Metodo'           => 'nullable|in:EFECTIVO,TARJETA,SINPE',
        ]);

        try {
            $r = $this->hotel->crearReserva(
                $d['HuespedId'], $d['TipoHabitacionId'], $d['FechaEntrada'], $d['FechaSalida'], $d['CantPersonas'],
                (float) ($d['Adelanto'] ?? 0), $d['Metodo'] ?? null,
                $request->boolean('EsperaDemo')   // demostración de concurrencia: retiene el bloqueo 10 s
            );
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.reservas.show', $r->ReservaId)
            ->with('ok', "Reserva #{$r->ReservaId} creada en la habitación {$r->Habitacion}.");
    }

    public function show(int $id)
    {
        $reserva = Reserva::with(['huesped.persona', 'detalles.habitacion.tipo', 'cargos.servicio', 'pagos'])->findOrFail($id);

        return view('admin.reservas.show', [
            'reserva'   => $reserva,
            'servicios' => Servicio::where('Activo', true)->orderBy('Nombre')->get(),
            'saldo'     => max(0, $reserva->Total - $reserva->pagado),
        ]);
    }

    public function checkIn(int $id)
    {
        return $this->ejecutar(fn () => $this->hotel->checkIn($id), 'Check-in realizado. La habitación quedó OCUPADA.');
    }

    public function cargo(Request $request, int $id)
    {
        $d = $request->validate([
            'ServicioId' => 'required|integer',
            'Cantidad'   => 'required|integer|min:1',
        ]);

        return $this->ejecutar(fn () => $this->hotel->registrarCargo($id, $d['ServicioId'], $d['Cantidad']), 'Consumo agregado.');
    }

    public function checkOut(Request $request, int $id)
    {
        $d = $request->validate([
            'Monto'  => 'nullable|numeric|min:0',
            'Metodo' => 'nullable|in:EFECTIVO,TARJETA,SINPE',
        ]);

        try {
            $r = $this->hotel->checkOut($id, (float) ($d['Monto'] ?? 0), $d['Metodo'] ?? null);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        $vuelto = $r->Vuelto > 0 ? ' Vuelto: ₡' . number_format($r->Vuelto, 0, ',', '.') . '.' : '';

        return back()->with('ok', 'Check-out realizado. La habitación quedó SUCIA.' . $vuelto);
    }

    public function cancelar(int $id)
    {
        try {
            $r = $this->hotel->cancelarReserva($id);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('ok', 'Reserva cancelada. Reembolso: ₡' . number_format($r->Reembolso, 0, ',', '.')
            . ' · Retenido: ₡' . number_format($r->Retenido, 0, ',', '.') . '.');
    }

    private function ejecutar(callable $accion, string $mensaje)
    {
        try {
            $accion();
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('ok', $mensaje);
    }
}

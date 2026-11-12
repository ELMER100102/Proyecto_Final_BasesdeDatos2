<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Huesped;
use App\Models\Reserva;
use App\Services\HotelService;
use Illuminate\Http\Request;
use RuntimeException;

class HuespedController extends Controller
{
    public function index(Request $request)
    {
        $huespedes = Huesped::with('persona')->withCount('reservas')
            ->when($request->buscar, fn ($q, $t) => $q->whereHas('persona', fn ($p) => $p
                ->where('Nombre', 'like', "%{$t}%")->orWhere('Apellidos', 'like', "%{$t}%")->orWhere('Correo', 'like', "%{$t}%")))
            ->get()
            ->sortBy(fn ($h) => $h->persona->nombre_completo);

        return view('admin.huespedes.index', compact('huespedes'));
    }

    public function create()
    {
        return view('admin.huespedes.create');
    }

    public function store(Request $request, HotelService $hotel)
    {
        $d = $request->validate([
            'Cedula'       => 'required|string|max:20',
            'Nombre'       => 'required|string|max:60',
            'Apellidos'    => 'required|string|max:100',
            'Correo'       => 'required|email|max:100|unique:Persona,Correo',
            'Telefono'     => 'nullable|string|max:20',
            'Nacionalidad' => 'required|string|max:30',
        ]);

        try {
            // sin contraseña, el huésped se registrará con el correo y recibirá un correo para crear su contraseña
            $id = $hotel->registrarPersona('HUESPED', $d['Cedula'], $d['Nombre'], $d['Apellidos'],
                strtolower($d['Correo']), $d['Telefono'] ?? null, $d['Nacionalidad']);
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.huespedes.show', $id)->with('ok', 'Huésped registrado.');
    }

    // Muestra la información del huésped y sus reservas
    public function show(int $id, HotelService $hotel)
    {
        $persona = $hotel->obtenerPersona($id);
        abort_if(! $persona, 404);

        return view('admin.huespedes.show', [
            'persona'  => $persona,
            'reservas' => Reserva::where('HuespedId', $id)->orderByDesc('FechaEntrada')->get(),
        ]);
    }
}

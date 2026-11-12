<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\TipoHabitacion;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class HabitacionController extends Controller
{
    public const ESTADOS = ['DISPONIBLE', 'OCUPADA', 'SUCIA', 'MANTENIMIENTO'];

    public function index()
    {
        $habitaciones = Habitacion::with('tipo')->orderBy('Numero')->get();
        return view('admin.habitaciones.index', compact('habitaciones'));
    }

    public function create()
    {
        return $this->formulario(new Habitacion(['Estado' => 'DISPONIBLE']));
    }

    public function store(Request $request)
    {
        Habitacion::create($this->datos($request));
        return redirect()->route('admin.habitaciones.index')->with('ok', 'Habitación creada.');
    }

    public function edit(Habitacion $habitacion)
    {
        return $this->formulario($habitacion);
    }

    public function update(Request $request, Habitacion $habitacion)
    {
        $habitacion->update($this->datos($request, $habitacion));
        return redirect()->route('admin.habitaciones.index')->with('ok', 'Habitación actualizada.');
    }

    public function destroy(Habitacion $habitacion)
    {
        try {
            $habitacion->delete();
        } catch (QueryException) {
            return back()->with('error', 'No se puede eliminar: la habitación tiene reservas.');
        }
        return back()->with('ok', 'Habitación eliminada.');
    }

    //CAMBIAR ESTADO DE HABITACION (DISPONIBLE, SUCIA, MANTENIMIENTO)
    public function estado(Request $request, Habitacion $habitacion)
    {
        $request->validate(['Estado' => 'required|in:DISPONIBLE,SUCIA,MANTENIMIENTO']);
        if ($habitacion->Estado === 'OCUPADA') {
            return back()->with('error', 'Una habitación ocupada solo cambia con el check-out.');
        }
        $habitacion->update(['Estado' => $request->Estado]);
        return back()->with('ok', "Habitación {$habitacion->Numero}: {$request->Estado}.");
    }

    private function formulario(Habitacion $habitacion)
    {
        return view('admin.habitaciones.form', [
            'habitacion' => $habitacion,
            'tipos'      => TipoHabitacion::orderBy('Nombre')->get(),
            'estados'    => self::ESTADOS,
        ]);
    }

    private function datos(Request $request, ?Habitacion $habitacion = null): array
    {
        return $request->validate([
            'TipoHabitacionId' => 'required|integer|exists:TipoHabitacion,TipoHabitacionId',
            'Numero'           => 'required|string|max:10|unique:Habitacion,Numero,' . ($habitacion?->HabitacionId ?? 'NULL') . ',HabitacionId',
            'Piso'             => 'required|integer|min:0|max:50',
            'Estado'           => 'required|in:' . implode(',', self::ESTADOS),
        ]);
    }
}

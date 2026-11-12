<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TipoHabitacion;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TipoHabitacionController extends Controller
{
    public function index()
    {
        $tipos = TipoHabitacion::withCount('habitaciones')->orderBy('PrecioNoche')->get();
        return view('admin.tipos.index', compact('tipos'));
    }

    public function create()
    {
        return view('admin.tipos.form', ['tipo' => new TipoHabitacion(['Activo' => true])]);
    }

    public function store(Request $request)
    {
        TipoHabitacion::create($this->datos($request));
        return redirect()->route('admin.tipos.index')->with('ok', 'Tipo de habitación creado.');
    }

    public function edit(TipoHabitacion $tipo)
    {
        return view('admin.tipos.form', compact('tipo'));
    }

    public function update(Request $request, TipoHabitacion $tipo)
    {
        $tipo->update($this->datos($request, $tipo));
        return redirect()->route('admin.tipos.index')->with('ok', 'Tipo de habitación actualizado.');
    }

    public function destroy(TipoHabitacion $tipo)
    {
        try {
            $tipo->delete();
        } catch (QueryException) {
            return back()->with('error', 'No se puede eliminar: tiene habitaciones asociadas. Desactívelo en su lugar.');
        }
        return back()->with('ok', 'Tipo de habitación eliminado.');
    }

    private function datos(Request $request, ?TipoHabitacion $tipo = null): array
    {
        $d = $request->validate([
            'Nombre'      => 'required|string|max:60|unique:TipoHabitacion,Nombre,' . ($tipo?->TipoHabitacionId ?? 'NULL') . ',TipoHabitacionId',
            'Descripcion' => 'nullable|string|max:500',
            'Capacidad'   => 'required|integer|min:1|max:12',
            'PrecioNoche' => 'required|numeric|min:1',
            'Foto'        => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('Foto')) {
            $d['FotoUrl'] = '/storage/' . $request->file('Foto')->store('habitaciones', 'public');
        }
        unset($d['Foto']);
        $d['Activo'] = $request->boolean('Activo');

        return $d;
    }
}

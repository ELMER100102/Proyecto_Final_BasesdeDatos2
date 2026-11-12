<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index()
    {
        return view('admin.servicios.index', ['servicios' => Servicio::orderBy('Nombre')->get()]);
    }

    public function create()
    {
        return view('admin.servicios.form', ['servicio' => new Servicio(['Activo' => true])]);
    }

    public function store(Request $request)
    {
        Servicio::create($this->datos($request));
        return redirect()->route('admin.servicios.index')->with('ok', 'Servicio creado.');
    }

    public function edit(Servicio $servicio)
    {
        return view('admin.servicios.form', compact('servicio'));
    }

    public function update(Request $request, Servicio $servicio)
    {
        $servicio->update($this->datos($request, $servicio));
        return redirect()->route('admin.servicios.index')->with('ok', 'Servicio actualizado.');
    }

    public function destroy(Servicio $servicio)
    {
        try {
            $servicio->delete();
        } catch (QueryException) {
            return back()->with('error', 'No se puede eliminar: ya se usó en consumos. Desactívelo en su lugar.');
        }
        return back()->with('ok', 'Servicio eliminado.');
    }

    private function datos(Request $request, ?Servicio $servicio = null): array
    {
        $d = $request->validate([
            'Nombre' => 'required|string|max:80|unique:Servicio,Nombre,' . ($servicio?->ServicioId ?? 'NULL') . ',ServicioId',
            'Precio' => 'required|numeric|min:1',
        ]);
        $d['Activo'] = $request->boolean('Activo');
        return $d;
    }
}

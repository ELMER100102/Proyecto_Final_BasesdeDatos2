<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mongo\Resena;
use Illuminate\Http\Request;

// Reseñas de MongoDB: responder y eliminar
class ResenaController extends Controller
{
    public function index()
    {
        return view('admin.resenas.index', ['resenas' => Resena::orderByDesc('fecha')->get()]);
    }

    public function responder(Request $request, string $id)
    {
        $request->validate(['respuestaHotel' => 'nullable|string|max:1000']);
        Resena::findOrFail($id)->update(['respuestaHotel' => $request->respuestaHotel]);
        return back()->with('ok', 'Respuesta guardada.');
    }

    public function destroy(string $id)
    {
        Resena::findOrFail($id)->delete();
        return back()->with('ok', 'Reseña eliminada.');
    }
}

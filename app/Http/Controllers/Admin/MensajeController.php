<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mongo\Mensaje;

//Mensajes de contacto de MongoDB
class MensajeController extends Controller
{
    public function index()
    {
        return view('admin.mensajes.index', [
            'mensajes' => Mensaje::orderBy('respondido')->orderByDesc('fecha')->get(),
        ]);
    }

    public function marcar(string $id)
    {
        $mensaje = Mensaje::findOrFail($id);
        $mensaje->update(['respondido' => ! $mensaje->respondido]);
        return back()->with('ok', $mensaje->respondido ? 'Marcado como respondido.' : 'Marcado como pendiente.');
    }

    public function destroy(string $id)
    {
        Mensaje::findOrFail($id)->delete();
        return back()->with('ok', 'Mensaje eliminado.');
    }
}

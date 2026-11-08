<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Mongo\Mensaje;
use Illuminate\Http\Request;

/** Formulario de contacto: los mensajes se guardan en MongoDB. */
class ContactoController extends Controller
{
    public function create()
    {
        return view('web.contacto');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'   => 'required|string|max:100',
            'correo'   => 'required|email|max:100',
            'telefono' => 'nullable|string|max:20',
            'asunto'   => 'required|string|max:150',
            'mensaje'  => 'required|string|max:2000',
        ]);

        Mensaje::create($datos + ['respondido' => false, 'fecha' => now()]);

        return redirect()->route('contacto')->with('ok', 'Mensaje enviado. Le responderemos pronto.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

//sOLO EXISTE UN REGISTRO DE HOTEL
class HotelController extends Controller
{
    public function edit()
    {
        return view('admin.hotel.edit', ['hotel' => Hotel::firstOrFail()]);
    }

    public function update(Request $request)
    {
        $hotel = Hotel::firstOrFail();

        $d = $request->validate([
            'Nombre'             => 'required|string|max:100',
            'Descripcion'        => 'nullable|string|max:500',
            'Direccion'          => 'required|string|max:255',
            'Latitud'            => 'nullable|numeric|between:-90,90',
            'Longitud'           => 'nullable|numeric|between:-180,180',
            'Telefono'           => 'required|string|max:20',
            'Correo'             => 'required|email|max:100',
            'PorcentajeImpuesto' => 'required|numeric|between:0,100',
            'Logo'               => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('Logo')) {
            $d['LogoUrl'] = '/storage/' . $request->file('Logo')->store('hotel', 'public');
        }
        unset($d['Logo']);

        $hotel->update($d);

        return back()->with('ok', 'Datos del hotel actualizados.');
    }
}

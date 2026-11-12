<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\ImagenSitio;
use Illuminate\Http\Request;

/** Imágenes del sitio: carrusel (SLIDE) y galería (GALERIA). */
class ImagenController extends Controller
{
    public function index()
    {
        return view('admin.imagenes.index', ['imagenes' => ImagenSitio::orderBy('Tipo')->orderBy('Orden')->get()]);
    }

    public function create()
    {
        return view('admin.imagenes.form', ['imagen' => new ImagenSitio(['Tipo' => 'GALERIA', 'Orden' => 1, 'Activo' => true])]);
    }

    public function store(Request $request)
    {
        $d = $this->datos($request, true);
        $d['HotelId'] = Hotel::firstOrFail()->HotelId;
        ImagenSitio::create($d);
        return redirect()->route('admin.imagenes.index')->with('ok', 'Imagen agregada.');
    }

    public function edit(ImagenSitio $imagen)
    {
        return view('admin.imagenes.form', compact('imagen'));
    }

    public function update(Request $request, ImagenSitio $imagen)
    {
        $imagen->update($this->datos($request, false));
        return redirect()->route('admin.imagenes.index')->with('ok', 'Imagen actualizada.');
    }

    public function destroy(ImagenSitio $imagen)
    {
        $imagen->delete();
        return back()->with('ok', 'Imagen eliminada.');
    }

    private function datos(Request $request, bool $nueva): array
    {
        $d = $request->validate([
            'Tipo'   => 'required|in:SLIDE,GALERIA',
            'Titulo' => 'nullable|string|max:100',
            'Orden'  => 'required|integer|min:1|max:255',
            'Imagen' => ($nueva ? 'required' : 'nullable') . '|image|max:4096',
        ]);

        if ($request->hasFile('Imagen')) {
            $d['ImagenUrl'] = '/storage/' . $request->file('Imagen')->store('sitio', 'public');
        }
        unset($d['Imagen']);
        $d['Activo'] = $request->boolean('Activo');

        return $d;
    }
}

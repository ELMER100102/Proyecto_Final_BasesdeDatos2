<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

// Usuarios del sistema. Los empleados se crean aquí; los huéspedes se registran en el sitio
class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        $usuarios = Usuario::with('persona')
            ->when($request->rol, fn ($q, $rol) => $q->where('Rol', $rol))
            ->get()
            ->sortBy(fn ($u) => $u->Rol . $u->persona->nombre_completo);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    // Nuevo empleado con su usuario (sp_RegistrarPersona, datos encriptados)
    public function store(Request $request, HotelService $hotel)
    {
        $d = $request->validate([
            'Cedula'     => 'required|string|max:20',
            'Nombre'     => 'required|string|max:60',
            'Apellidos'  => 'required|string|max:100',
            'Correo'     => 'required|email|max:100|unique:Persona,Correo',
            'Telefono'   => 'nullable|string|max:20',
            'Puesto'     => 'required|in:ADMINISTRADOR,RECEPCIONISTA',
            'Salario'    => 'required|numeric|min:1',
            'Contrasena' => 'required|string|min:8',
        ]);

        try {
            $hotel->registrarPersona('EMPLEADO', $d['Cedula'], $d['Nombre'], $d['Apellidos'], strtolower($d['Correo']),
                $d['Telefono'] ?? null, null, $d['Puesto'], (float) $d['Salario'], Hash::make($d['Contrasena']));
        } catch (RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('admin.usuarios.index')->with('ok', 'Empleado creado.');
    }

    public function edit(Usuario $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, Usuario $usuario)
    {
        $d = $request->validate([
            'Rol'        => 'required|in:ADMINISTRADOR,RECEPCIONISTA,HUESPED',
            'Contrasena' => 'nullable|string|min:8',
        ]);

        if ($usuario->is($request->user()) && ($d['Rol'] !== 'ADMINISTRADOR' || ! $request->boolean('Activo'))) {
            return back()->with('error', 'No puede quitarse a sí mismo el rol de administrador ni desactivarse.');
        }

        $usuario->Rol = $d['Rol'];
        $usuario->Activo = $request->boolean('Activo');
        if (! empty($d['Contrasena'])) {
            $usuario->ContrasenaHash = Hash::make($d['Contrasena']);
        }
        $usuario->save();

        return redirect()->route('admin.usuarios.index')->with('ok', 'Usuario actualizado.');
    }
}

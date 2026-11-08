<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Services\HotelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        return view('web.login');
    }

    public function login(Request $request)
    {
        $datos = $request->validate([
            'correo'     => 'required|email',
            'contrasena' => 'required',
        ]);

        // El correo está en Persona; la contraseña y el rol en Usuario
        $usuario = Usuario::whereHas('persona', fn ($q) => $q->where('Correo', strtolower($datos['correo'])))->first();

        if (! $usuario || ! $usuario->Activo || ! Hash::check($datos['contrasena'], $usuario->ContrasenaHash)) {
            return back()->withInput()->withErrors(['correo' => 'Correo o contraseña incorrectos.']);
        }

        Auth::login($usuario);
        $request->session()->regenerate();

        // Empleados al panel; huéspedes al sitio
        return $usuario->esEmpleado()
            ? redirect()->route('admin.dashboard')
            : redirect()->intended(route('inicio'));
    }

    public function mostrarRegistro()
    {
        return view('web.registro');
    }

    /** Registro de huéspedes desde el sitio: usa sp_RegistrarPersona (cédula y teléfono encriptados) */
    public function registro(Request $request, HotelService $hotel)
    {
        $datos = $request->validate([
            'cedula'       => 'required|string|max:20',
            'nombre'       => 'required|string|max:60',
            'apellidos'    => 'required|string|max:100',
            'correo'       => 'required|email|max:100|unique:Persona,Correo',
            'telefono'     => 'nullable|string|max:20',
            'nacionalidad' => 'required|string|max:30',
            'contrasena'   => 'required|string|min:8|confirmed',
        ]);

        try {
            $personaId = $hotel->registrarPersona(
                'HUESPED', $datos['cedula'], $datos['nombre'], $datos['apellidos'], strtolower($datos['correo']),
                $datos['telefono'] ?? null, $datos['nacionalidad'], null, null, Hash::make($datos['contrasena'])
            );
        } catch (RuntimeException $e) {
            return back()->withInput()->withErrors(['correo' => $e->getMessage()]);
        }

        Auth::login(Usuario::where('PersonaId', $personaId)->firstOrFail());
        $request->session()->regenerate();

        return redirect()->route('inicio')->with('ok', '¡Bienvenido! Su cuenta fue creada.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('inicio');
    }
}

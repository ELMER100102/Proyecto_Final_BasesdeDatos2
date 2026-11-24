<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mongo\Respaldo;
use App\Services\RespaldoService;
use Illuminate\Support\Facades\Auth;
use Throwable;

/** Botón de respaldo de SQL Server, con descarga e historial guardado en MongoDB. */
class RespaldoController extends Controller
{
    public function __construct(private RespaldoService $respaldos)
    {
    }

    public function index()
    {
        return view('admin.respaldos.index', [
            'historial' => Respaldo::orderByDesc('fecha')->limit(50)->get(),
            'carpeta'   => $this->respaldos->carpeta(),
            'servicio'  => $this->respaldos,
        ]);
    }

    public function sqlServer()
    {
        try {
            $r = $this->respaldos->sqlServer();
            $respaldo = $this->registrar([
                'archivo' => $r['Archivo'],
                'ruta'    => $r['Ruta'],
                'bytes'   => is_file($r['Ruta']) ? filesize($r['Ruta']) : null,
            ]);

            return back()->with('ok', "Respaldo creado: {$r['Archivo']}")
                         ->with('respaldo_nuevo', $respaldo?->id);
        } catch (Throwable $e) {
            $mensaje = preg_replace('/^.*\[SQL Server\]/', '', $e->getMessage());
            $this->registrar(['estado' => 'ERROR', 'error' => $mensaje]);

            return back()->with('error', 'No se pudo respaldar la base: ' . $mensaje);
        }
    }

    public function descargar(string $id)
    {
        $r = Respaldo::findOrFail($id);
        abort_unless($this->respaldos->descargable($r->ruta), 404, 'El archivo ya no está disponible.');

        return response()->download($r->ruta, $r->archivo);
    }

    private function registrar(array $datos): ?Respaldo
    {
        try {
            return Respaldo::create($datos + [
                'tipo'    => 'SQL Server',
                'usuario' => Auth::user()?->persona?->Correo,
                'fecha'   => now(),
                'estado'  => 'OK',
            ]);
        } catch (Throwable) {
            return null;   // Si Mongo no responde, el respaldo igual se hizo; solo no queda en el historial.
        }
    }
}

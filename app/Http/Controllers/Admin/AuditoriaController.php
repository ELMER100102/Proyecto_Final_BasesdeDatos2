<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditoria;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Historial de cambios registrado por los triggers (tabla Auditoria).
 * La aplicación no puede leer la tabla directamente (DENY SELECT):
 * la consulta pasa por el procedimiento sp_ConsultarAuditoria.
 */
class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->input('desde', now()->subDays(30)->toDateString());
        $hasta = $request->input('hasta', now()->toDateString());
        $tabla = $request->input('tabla') ?: null;

        try {
            $filas = DB::select('EXEC sp_ConsultarAuditoria ?, ?, ?', [$desde, $hasta, $tabla]);
        } catch (QueryException $e) {
            $filas = [];
            session()->now('error', 'No se pudo consultar la auditoría: ' . preg_replace('/^.*\[SQL Server\]/', '', $e->getPrevious()?->getMessage() ?? $e->getMessage()));
        }

        return view('admin.auditoria.index', [
            // hydrate: convierte las filas del procedimiento en modelos (fechas y JSON listos)
            'registros' => Auditoria::hydrate(array_map(fn ($f) => (array) $f, $filas)),
            'tablas'    => ['Pago', 'Reserva'],
            'desde'     => $desde,
            'hasta'     => $hasta,
        ]);
    }
}

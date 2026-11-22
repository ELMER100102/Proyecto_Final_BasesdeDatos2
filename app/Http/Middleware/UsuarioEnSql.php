<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Le dice a SQL Server qué usuario de la aplicación está haciendo la petición.
 * La aplicación siempre se conecta con el mismo login (app_hotel), así que sin
 * esto la auditoría no sabría si fue la recepcionista o el administrador.
 * Los triggers lo leen con SESSION_CONTEXT(N'usuario').
 */
class UsuarioEnSql
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            try {
                // El valor pasa primero por una variable NVARCHAR(256):
                // el driver de PHP envía los textos como NVARCHAR(MAX) y
                // sp_set_session_context (tipo sql_variant) no acepta NVARCHAR(MAX).
                DB::statement(
                    "DECLARE @Usuario NVARCHAR(256) = ?;
                     EXEC sp_set_session_context @key = N'usuario', @value = @Usuario;",
                    [Auth::user()->persona->Correo]
                );
            } catch (Throwable $e) {
                // La página sigue funcionando; la auditoría usará el login de SQL Server.
                Log::warning('UsuarioEnSql: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
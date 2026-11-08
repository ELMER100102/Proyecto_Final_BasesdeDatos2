<?php

namespace App\Services;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * lugar de Laravel que llama a los procedimientos almacenados de HotelDB.
 */
class HotelService
{
    //Disponibilidad de habitaciones: devuelve tipos de habitación con al menos una libre entre esas fechas
    public function disponibilidad(string $entrada, string $salida, int $personas): array
    {
        return DB::select("
            SELECT t.TipoHabitacionId, t.Nombre, t.Descripcion, t.Capacidad, t.PrecioNoche, t.FotoUrl,
                   COUNT(h.HabitacionId) AS Libres
            FROM TipoHabitacion t
            JOIN Habitacion h ON h.TipoHabitacionId = t.TipoHabitacionId
            WHERE t.Activo = 1
              AND t.Capacidad >= ?
              AND h.Estado <> 'MANTENIMIENTO'
              AND NOT EXISTS (SELECT 1
                              FROM DetalleReserva d
                              JOIN Reserva r ON r.ReservaId = d.ReservaId
                              WHERE d.HabitacionId = h.HabitacionId
                                AND r.Estado IN ('PENDIENTE', 'CONFIRMADA', 'EN_CURSO')
                                AND r.FechaEntrada < ?
                                AND r.FechaSalida > ?)
            GROUP BY t.TipoHabitacionId, t.Nombre, t.Descripcion, t.Capacidad, t.PrecioNoche, t.FotoUrl
            ORDER BY t.PrecioNoche", [$personas, $salida, $entrada]);
    }

    //RESERVAS

    public function crearReserva(int $huespedId, int $tipoHabitacionId, string $fechaEntrada, string $fechaSalida,
                                 int $personas, float $adelanto = 0, ?string $metodo = null, bool $esperaDemo = false): object
    {
        return $this->select('sp_CrearReserva', [
            $huespedId, $tipoHabitacionId, $fechaEntrada, $fechaSalida, $personas, $adelanto, $metodo, (int) $esperaDemo,
        ])[0];
    }

    public function checkIn(int $reservaId): void
    {
        $this->ejecutar('sp_CheckIn', [$reservaId]);
    }

    public function checkOut(int $reservaId, float $monto = 0, ?string $metodo = null): object
    {
        return $this->select('sp_CheckOut', [$reservaId, $monto, $metodo])[0];
    }

    public function cancelarReserva(int $reservaId): object
    {
        return $this->select('sp_CancelarReserva', [$reservaId])[0];
    }

    public function registrarCargo(int $reservaId, int $servicioId, int $cantidad = 1): void
    {
        $this->select('sp_Cargo_Insertar', [$reservaId, $servicioId, $cantidad]);
    }

    //PERSONAS (datos encriptados)

    /** $tipo: 'HUESPED' o 'EMPLEADO'. Devuelve el PersonaId nuevo. */
    public function registrarPersona(string $tipo, string $cedula, string $nombre, string $apellidos, string $correo,
                                     ?string $telefono = null, ?string $nacionalidad = 'Costa Rica',
                                     ?string $puesto = null, ?float $salario = null, ?string $contrasenaHash = null): int
    {
        $fila = $this->select('sp_RegistrarPersona', [
            $tipo, $cedula, $nombre, $apellidos, $correo, $telefono, $nacionalidad, $puesto, $salario, $contrasenaHash,
        ])[0];

        return (int) $fila->PersonaId;
    }

    public function obtenerPersona(int $personaId): ?object
    {
        return $this->select('sp_ObtenerPersona', [$personaId])[0] ?? null;
    }

    //REPORTE DE INGRESOS

    public function reporteIngresos(string $desde, string $hasta): array
    {
        return $this->select('sp_ReporteIngresos', [$desde, $hasta]);
    }

    //Ayudantes

    // Procedimiento que devuelve filas
    private function select(string $procedimiento, array $parametros = []): array
    {
        try {
            return DB::select($this->sql($procedimiento, $parametros), $parametros);
        } catch (QueryException $e) {
            throw $this->traducir($e);
        }
    }

    // Procedimiento que no devuelve filas
    private function ejecutar(string $procedimiento, array $parametros = []): void
    {
        try {
            DB::statement($this->sql($procedimiento, $parametros), $parametros);
        } catch (QueryException $e) {
            throw $this->traducir($e);
        }
    }

    private function sql(string $procedimiento, array $parametros): string
    {
        $marcas = implode(', ', array_fill(0, count($parametros), '?'));
        return trim("EXEC {$procedimiento} {$marcas}");
    }

    /**
     * SQL Server devuelve algo como:
     * SQLSTATE[42000]: [Microsoft][ODBC Driver 18 for SQL Server][SQL Server]No hay habitaciones disponibles...
     * Si es un error propio (50000 o más) se deja solo el texto final.
     */
    private function traducir(QueryException $e): RuntimeException
    {
        $codigo = (int) ($e->errorInfo[1] ?? 0);

        if ($codigo >= 50000) {
            $mensaje = $e->errorInfo[2] ?? $e->getMessage();
            $mensaje = preg_replace('/^(\[[^\]]*\])+\s*/', '', $mensaje);
            return new RuntimeException($mensaje, $codigo, $e);
        }

        report($e);
        return new RuntimeException('Ocurrió un error con la base de datos. Intente de nuevo.', $codigo, $e);
    }
}

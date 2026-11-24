<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use PDO;
use RuntimeException;

/** Respaldo completo de la base de SQL Server (.bak). */
class RespaldoService
{
    /** Carpeta configurada en el .env (RESPALDOS_CARPETA), sin barra final. */
    public function carpeta(): ?string
    {
        $carpeta = config('respaldos.carpeta');

        return $carpeta ? rtrim($carpeta, '\\/') : null;
    }

    /**
     * Respaldo completo de SQL Server con sp_RespaldarBase.
     * Se usa PDO directo y se recorren TODOS los resultados: BACKUP envía mensajes
     * de progreso y, si PHP cierra la consulta antes, el respaldo se cancela.
     */
    public function sqlServer(): array
    {
        $pdo  = DB::connection('sqlsrv')->getPdo();
        $stmt = $pdo->prepare('EXEC sp_RespaldarBase ?');
        $stmt->execute([$this->carpeta()]);

        $fila = null;
        do {
            if ($stmt->columnCount() > 0) {
                $fila = $stmt->fetch(PDO::FETCH_ASSOC) ?: $fila;
            }
        } while ($stmt->nextRowset());

        if (! $fila) {
            throw new RuntimeException('SQL Server no devolvió el resultado del respaldo.');
        }

        return $fila;   // Archivo, Ruta, Fecha
    }

    /// Verifica que la ruta esté dentro de la carpeta de respaldos y sea legible.
    public function descargable(?string $ruta): bool
    {
        $carpeta = $this->carpeta();
        if (! $carpeta || ! $ruta) {
            return false;
        }

        $real = realpath($ruta);

        return $real !== false
            && is_file($real)
            && is_readable($real)
            && str_starts_with(strtolower($real), strtolower(realpath($carpeta) ?: $carpeta) . DIRECTORY_SEPARATOR);
    }
}

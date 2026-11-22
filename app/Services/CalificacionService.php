<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * Calificación promedio por tipo de habitación, calculada en MongoDB con
 * una agregación ($group + $avg), no en PHP.
 *
 * Equivale en mongosh a:
 *   db.resenas.aggregate([
 *     { $group: { _id: "$tipoHabitacion", promedio: { $avg: "$calificacion" }, total: { $sum: 1 } } },
 *     { $sort: { promedio: -1 } }
 *   ])
 */
class CalificacionService
{
    private ?array $cache = null;

    /** @return array<string, array{promedio: float, total: int}>  Clave: nombre del tipo de habitación */
    public function promedios(): array
    {
        if ($this->cache !== null) {
            return $this->cache;   // una sola consulta a Mongo por petición
        }

        try {
            $cursor = DB::connection('mongodb')->getCollection('resenas')->aggregate([
                ['$group' => [
                    '_id'      => '$tipoHabitacion',
                    'promedio' => ['$avg' => '$calificacion'],
                    'total'    => ['$sum' => 1],
                ]],
                ['$sort' => ['promedio' => -1]],
            ], ['typeMap' => ['root' => 'array', 'document' => 'array']]);

            $resultado = [];
            foreach ($cursor as $fila) {
                $resultado[$fila['_id']] = [
                    'promedio' => round((float) $fila['promedio'], 1),
                    'total'    => (int) $fila['total'],
                ];
            }
        } catch (Throwable) {
            $resultado = [];       // si MongoDB no responde, el sitio sigue funcionando sin estrellas
        }

        return $this->cache = $resultado;
    }
}

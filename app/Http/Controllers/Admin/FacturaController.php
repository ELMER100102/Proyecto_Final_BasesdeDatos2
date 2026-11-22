<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Reserva;
use App\Services\HotelService;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Comprobante en PDF de una reserva.
 * EN_CURSO   -> "Estado de cuenta" (lo que lleva consumido hasta ahora)
 * FINALIZADA -> "Factura" (después del check-out)
 */
class FacturaController extends Controller
{
    public function show(int $id, HotelService $servicio)
    {
        $reserva = Reserva::with(['detalles.habitacion.tipo', 'cargos.servicio', 'pagos'])->findOrFail($id);

        if (! in_array($reserva->Estado, ['EN_CURSO', 'FINALIZADA'])) {
            return back()->with('error', 'El comprobante solo está disponible para reservas en curso o finalizadas.');
        }

        $hotel     = Hotel::firstOrFail();
        $hospedaje = (float) $reserva->detalles->sum('Subtotal');
        $consumos  = (float) $reserva->cargos->sum(fn ($c) => $c->subtotal);
        $total     = (float) $reserva->Total;          // calculado por los SP y el trigger de cargos
        $pagado    = $reserva->pagado;

        $pdf = Pdf::loadView('admin.reservas.factura', [
            'reserva'   => $reserva,
            'hotel'     => $hotel,
            'logo'      => $this->logoEnBase64($hotel->LogoUrl),
            // Datos del huésped desencriptados por sp_ObtenerPersona (cédula y teléfono)
            'huesped'   => $servicio->obtenerPersona($reserva->HuespedId),
            'titulo'    => $reserva->Estado === 'FINALIZADA' ? 'Factura' : 'Estado de cuenta',
            'hospedaje' => $hospedaje,
            'consumos'  => $consumos,
            'impuesto'  => round($total - $hospedaje - $consumos, 2),
            'total'     => $total,
            'pagado'    => $pagado,
            'saldo'     => max(0, round($total - $pagado, 2)),
        ])->setPaper('letter');

        return $pdf->stream(sprintf('factura-%06d.pdf', $reserva->ReservaId));
    }

    /** dompdf no siempre puede leer /storage/...: se incrusta el logo como data URI. */
    private function logoEnBase64(?string $url): ?string
    {
        $ruta = $url ? public_path(ltrim($url, '/')) : null;
        if (! $ruta || ! is_file($ruta)) {
            return null;
        }

        return 'data:' . mime_content_type($ruta) . ';base64,' . base64_encode(file_get_contents($ruta));
    }
}

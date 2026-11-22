<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>{{ $titulo }} {{ sprintf('%06d', $reserva->ReservaId) }}</title>
    <style>
        /* dompdf entiende CSS 2.1: tablas y floats, nada de flex ni grid */
        @page {
            margin: 28px 36px;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10.5px;
            color: #44403b;
        }

        h1,
        h2 {
            color: #1c1917;
            margin: 0;
        }

        .franja {
            background: #0d542b;
            color: #fcf7ed;
            padding: 16px 18px;
        }

        .franja td {
            color: #fcf7ed;
            vertical-align: middle;
        }

        .franja .nombre {
            font-size: 18px;
            font-weight: bold;
        }

        .franja .doc {
            text-align: right;
        }

        .franja .doc .tipo {
            font-size: 16px;
            font-weight: bold;
            color: #b9ff78;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .bloque {
            margin-top: 18px;
        }

        .etiqueta {
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #78716c;
        }

        .datos td {
            padding: 2px 0;
            vertical-align: top;
            width: 50%;
        }

        .lineas th {
            background: #fcf7ed;
            color: #292524;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: .6px;
            text-align: left;
            padding: 6px 8px;
            border-bottom: 1px solid #d6d3d1;
        }

        .lineas td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
        }

        .num {
            text-align: right;
            white-space: nowrap;
        }

        .totales {
            width: 45%;
            margin-left: 55%;
            margin-top: 10px;
        }

        .totales td {
            padding: 4px 8px;
        }

        .totales .total td {
            border-top: 2px solid #1c1917;
            font-size: 13px;
            font-weight: bold;
            color: #1c1917;
            padding-top: 8px;
        }

        .saldo {
            background: #b9ff78;
            color: #1c1917;
            font-weight: bold;
        }

        .pie {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8.5px;
            color: #78716c;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
            text-align: center;
        }

        .linea-coral {
            border-top: 2px solid #f54320;
            width: 60px;
            margin: 4px 0 8px;
        }
    </style>
</head>

<body>

    {{-- Encabezado --}}
    <div class="franja">
        <table>
            <tr>
                <td>
                    @if ($logo)
                        <img src="{{ $logo }}" alt=""
                            style="height:38px; vertical-align:middle; margin-right:8px">
                    @endif
                    <span class="nombre">{{ $hotel->Nombre }}</span><br>
                    {{ $hotel->Direccion }}<br>
                    Tel. {{ $hotel->Telefono }} · {{ $hotel->Correo }}
                </td>
                <td class="doc">
                    <span class="tipo">{{ $titulo }}</span><br>
                    N.º {{ sprintf('%06d', $reserva->ReservaId) }}<br>
                    Emitida: {{ now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Huésped y estadía --}}
    <table class="bloque datos">
        <tr>
            <td>
                <div class="etiqueta">Huésped</div>
                <div class="linea-coral"></div>
                <strong>{{ $huesped->Nombre ?? '' }} {{ $huesped->Apellidos ?? '' }}</strong><br>
                Cédula: {{ $huesped->Cedula ?? '—' }}<br>
                {{ $huesped->Correo ?? '' }}@if (!empty($huesped->Telefono))
                    · Tel. {{ $huesped->Telefono }}
                @endif
            </td>
            <td>
                <div class="etiqueta">Estadía</div>
                <div class="linea-coral"></div>
                Entrada: <strong>{{ $reserva->FechaEntrada->format('d/m/Y') }}</strong><br>
                Salida: <strong>{{ $reserva->FechaSalida->format('d/m/Y') }}</strong><br>
                Personas: {{ $reserva->CantPersonas }} · Estado: {{ str_replace('_', ' ', $reserva->Estado) }}
            </td>
        </tr>
    </table>

    {{-- Detalle --}}
    <table class="bloque lineas">
        <thead>
            <tr>
                <th>Descripción</th>
                <th class="num">Cantidad</th>
                <th class="num">Precio</th>
                <th class="num">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reserva->detalles as $d)
                <tr>
                    <td>Hospedaje · Habitación {{ $d->habitacion->Numero }} ({{ $d->habitacion->tipo->Nombre }})</td>
                    <td class="num">{{ $d->Noches }} {{ $d->Noches == 1 ? 'noche' : 'noches' }}</td>
                    <td class="num">₡{{ number_format($d->PrecioNoche, 0, ',', '.') }}</td>
                    <td class="num">₡{{ number_format($d->Subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @foreach ($reserva->cargos as $c)
                <tr>
                    <td>{{ $c->servicio->Nombre }} <span style="color:#78716c">·
                            {{ $c->Fecha?->format('d/m') }}</span></td>
                    <td class="num">{{ $c->Cantidad }}</td>
                    <td class="num">₡{{ number_format($c->PrecioUnitario, 0, ',', '.') }}</td>
                    <td class="num">₡{{ number_format($c->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Totales --}}
    <table class="totales">
        <tr>
            <td>Hospedaje</td>
            <td class="num">₡{{ number_format($hospedaje, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Consumos</td>
            <td class="num">₡{{ number_format($consumos, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Impuesto ({{ rtrim(rtrim(number_format($hotel->PorcentajeImpuesto, 2, '.', ''), '0'), '.') }} %)</td>
            <td class="num">₡{{ number_format($impuesto, 0, ',', '.') }}</td>
        </tr>
        <tr class="total">
            <td>Total</td>
            <td class="num">₡{{ number_format($total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Pagado</td>
            <td class="num">₡{{ number_format($pagado, 0, ',', '.') }}</td>
        </tr>
        <tr class="saldo">
            <td>Saldo pendiente</td>
            <td class="num">₡{{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>
    </table>

    {{-- Pagos --}}
    @if ($reserva->pagos->isNotEmpty())
        <table class="bloque lineas">
            <thead>
                <tr>
                    <th>Pago</th>
                    <th>Fecha</th>
                    <th>Método</th>
                    <th class="num">Monto</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reserva->pagos as $p)
                    <tr>
                        <td>{{ ucfirst(strtolower($p->Tipo)) }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($p->Fecha)->format('d/m/Y H:i') }}</td>
                        <td>{{ ucfirst(strtolower($p->Metodo)) }}</td>
                        <td class="num">
                            {{ $p->Tipo === 'REEMBOLSO' ? '−' : '' }}₡{{ number_format($p->Monto, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="pie">
        {{ $hotel->Nombre }} · Gracias por su visita ·
        Documento generado por el sistema de reservas — proyecto académico, sin validez como comprobante electrónico de
        Hacienda.
    </div>

</body>

</html>

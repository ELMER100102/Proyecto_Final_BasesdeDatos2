@extends('layouts.admin')
@section('titulo', 'Reporte de ingresos')

@section('contenido')
    <form class="d-flex flex-wrap gap-2 align-items-end mb-3 no-imprimir" method="GET">
        <div><label class="form-label" for="desde">Desde</label><input type="date" id="desde" name="desde"
                value="{{ $desde }}" class="form-control"></div>
        <div><label class="form-label" for="hasta">Hasta</label><input type="date" id="hasta" name="hasta"
                value="{{ $hasta }}" class="form-control"></div>
        <button class="btn btn-primary">Generar</button>
        <button type="button" class="btn btn-outline-secondary" onclick="window.print()">Imprimir</button>
    </form>

    <p class="text-muted small no-imprimir">Reservas FINALIZADAS por mes de entrada y tipo de habitación. Generado con el procedimiento
        sp_ReporteIngresos.</p>

    <div class="card">
        <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Tipo de habitación</th>
                    <th class="text-end">Reservas</th>
                    <th class="text-end">Noches</th>
                    <th class="text-end">Hospedaje</th>
                    <th class="text-end">Consumos</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($filas as $f)
                    <tr>
                        <td>{{ $f->Mes }}</td>
                        <td>{{ $f->TipoHabitacion }}</td>
                        <td class="text-end">{{ $f->Reservas }}</td>
                        <td class="text-end">{{ $f->Noches }}</td>
                        <td class="text-end">₡{{ number_format($f->IngresoHospedaje, 0, ',', '.') }}</td>
                        <td class="text-end">₡{{ number_format($f->IngresoConsumos, 0, ',', '.') }}</td>
                        <td class="text-end fw-semibold">
                            ₡{{ number_format($f->IngresoHospedaje + $f->IngresoConsumos, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No hay reservas finalizadas en ese rango.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            @if ($filas->isNotEmpty())
                <tfoot class="fw-bold">
                    <tr>
                        <td colspan="2">Total</td>
                        <td class="text-end">{{ $totales['Reservas'] }}</td>
                        <td class="text-end">{{ $totales['Noches'] }}</td>
                        <td class="text-end">₡{{ number_format($totales['IngresoHospedaje'], 0, ',', '.') }}</td>
                        <td class="text-end">₡{{ number_format($totales['IngresoConsumos'], 0, ',', '.') }}</td>
                        <td class="text-end">
                            ₡{{ number_format($totales['IngresoHospedaje'] + $totales['IngresoConsumos'], 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            @endif
        </table>
        </div>
    </div>
@endsection

@extends('layouts.admin')
@section('titulo', 'Reservas')

@section('contenido')
<div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
    <form class="d-flex gap-2" method="GET">
        <select name="estado" class="form-select w-auto" aria-label="Filtrar por estado" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach (['PENDIENTE', 'CONFIRMADA', 'EN_CURSO', 'FINALIZADA', 'CANCELADA'] as $e)
                <option value="{{ $e }}" @selected(request('estado') === $e)>{{ str_replace('_', ' ', $e) }}</option>
            @endforeach
        </select>
    </form>
    <a href="{{ route('admin.reservas.create') }}" class="btn btn-primary">Nueva reserva</a>
</div>

<div class="card">
    <div class="card-body">
        {{-- data-order: ordena por defecto por Entrada (columna 3) descendente --}}
        <table class="table table-hover align-middle datatable w-100" data-order='[[3, "desc"]]'>
            <thead>
                <tr><th>#</th><th>Huésped</th><th>Habitación</th><th>Entrada</th><th>Salida</th><th>Estado</th><th class="text-end">Total</th><th class="no-sort"></th></tr>
            </thead>
            <tbody>
                @foreach ($reservas as $r)
                    <tr>
                        <td>{{ $r->ReservaId }}</td>
                        <td>{{ $r->huesped->persona->nombre_completo }}<div class="small text-muted">{{ $r->huesped->persona->Correo }}</div></td>
                        <td>{{ $r->detalles->pluck('habitacion.Numero')->join(', ') }}</td>
                        {{-- data-order: se ve dd/mm/aaaa pero se ordena como fecha --}}
                        <td data-order="{{ $r->FechaEntrada->format('Y-m-d') }}">{{ $r->FechaEntrada->format('d/m/Y') }}</td>
                        <td data-order="{{ $r->FechaSalida->format('Y-m-d') }}">{{ $r->FechaSalida->format('d/m/Y') }}</td>
                        <td>@include('admin.parts.estado', ['estado' => $r->Estado])</td>
                        <td class="text-end" data-order="{{ $r->Total }}">₡{{ number_format($r->Total, 0, ',', '.') }}</td>
                        <td class="text-end"><a href="{{ route('admin.reservas.show', $r->ReservaId) }}" class="btn btn-sm btn-outline-dark">Abrir</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

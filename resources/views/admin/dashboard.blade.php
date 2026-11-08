@extends('layouts.admin')
@section('titulo', 'Inicio del panel')

@section('contenido')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small">Huéspedes hospedados</div>
            <div class="fs-3 fw-bold">{{ $enCurso }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small">Llegadas de hoy</div>
            <div class="fs-3 fw-bold">{{ $llegadasHoy }}</div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small">Habitaciones disponibles</div>
            <div class="fs-3 fw-bold">{{ $habitaciones['DISPONIBLE'] ?? 0 }}</div>
            <div class="small text-muted">
                Ocupadas {{ $habitaciones['OCUPADA'] ?? 0 }} · Sucias {{ $habitaciones['SUCIA'] ?? 0 }}
            </div>
        </div></div>
    </div>
    <div class="col-md-3">
        <div class="card"><div class="card-body">
            <div class="text-muted small">Mensajes sin responder</div>
            <div class="fs-3 fw-bold">{{ $mensajes }}</div>
        </div></div>
    </div>
</div>

<div class="card">
    <div class="card-header">Últimas reservas</div>
    <table class="table mb-0">
        <thead>
            <tr><th>#</th><th>Huésped</th><th>Entrada</th><th>Salida</th><th>Estado</th><th class="text-end">Total</th></tr>
        </thead>
        <tbody>
            @foreach ($ultimas as $r)
                <tr>
                    <td>{{ $r->ReservaId }}</td>
                    <td>{{ $r->huesped->persona->nombre_completo }}</td>
                    <td>{{ $r->FechaEntrada->format('d/m/Y') }}</td>
                    <td>{{ $r->FechaSalida->format('d/m/Y') }}</td>
                    <td><span class="badge bg-secondary">{{ $r->Estado }}</span></td>
                    <td class="text-end">₡{{ number_format($r->Total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

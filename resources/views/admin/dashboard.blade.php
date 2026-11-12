@extends('layouts.admin')
@section('titulo', 'Inicio del panel')

@section('contenido')
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card tarjeta-dato tarjeta-dato--forest">
                <div class="card-body">
                    <div class="tarjeta-dato__etiqueta">Huéspedes hospedados</div>
                    <div class="tarjeta-dato__cifra">{{ $enCurso }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card tarjeta-dato tarjeta-dato--moss">
                <div class="card-body">
                    <div class="tarjeta-dato__etiqueta">Llegadas de hoy</div>
                    <div class="tarjeta-dato__cifra">{{ $llegadasHoy }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card tarjeta-dato tarjeta-dato--lima">
                <div class="card-body">
                    <div class="tarjeta-dato__etiqueta">Habitaciones disponibles</div>
                    <div class="tarjeta-dato__cifra">{{ $habitaciones['DISPONIBLE'] ?? 0 }}</div>
                    <div class="tarjeta-dato__nota">Ocupadas {{ $habitaciones['OCUPADA'] ?? 0 }} · Sucias
                        {{ $habitaciones['SUCIA'] ?? 0 }} · Mant. {{ $habitaciones['MANTENIMIENTO'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card tarjeta-dato tarjeta-dato--coral">
                <div class="card-body">
                    <div class="tarjeta-dato__etiqueta">Mensajes sin responder</div>
                    <div class="tarjeta-dato__cifra">{{ $mensajes }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <span>Últimas reservas</span>
            <a href="{{ route('admin.reservas.create') }}" class="btn btn-sm btn-primary">Nueva reserva</a>
        </div>
        <table class="table table-hover mb-0 align-middle datatable w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Huésped</th>
                    <th>Entrada</th>
                    <th>Salida</th>
                    <th>Estado</th>
                    <th class="text-end">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ultimas as $r)
                    <tr>
                        <td>{{ $r->ReservaId }}</td>
                        <td>{{ $r->huesped->persona->nombre_completo }}</td>
                        <td>{{ $r->FechaEntrada->format('d/m/Y') }}</td>
                        <td>{{ $r->FechaSalida->format('d/m/Y') }}</td>
                        <td>@include('admin.parts.estado', ['estado' => $r->Estado])</td>
                        <td class="text-end">₡{{ number_format($r->Total, 0, ',', '.') }}</td>
                        <td class="text-end"><a href="{{ route('admin.reservas.show', $r->ReservaId) }}"
                                class="btn btn-sm btn-outline-dark">Abrir</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

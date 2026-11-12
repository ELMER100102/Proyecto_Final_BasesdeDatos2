@extends('layouts.admin')
@section('titulo', $persona->Nombre . ' ' . $persona->Apellidos)

@section('contenido')
<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">Datos del huésped</div>
            <div class="card-body">
                <dl class="mb-0">
                    <dt>Nombre</dt><dd>{{ $persona->Nombre }} {{ $persona->Apellidos }}</dd>
                    <dt>Cédula</dt><dd>{{ $persona->Cedula }}</dd>
                    <dt>Teléfono</dt><dd>{{ $persona->Telefono ?? '—' }}</dd>
                    <dt>Correo</dt><dd>{{ $persona->Correo }}</dd>
                    <dt>Nacionalidad</dt><dd>{{ $persona->Nacionalidad ?? '—' }}</dd>
                </dl>
                <p class="small text-muted mt-3 mb-0">Cédula y teléfono desencriptados por sp_ObtenerPersona.</p>
            </div>
        </div>
        <a href="{{ route('admin.reservas.create') }}" class="btn btn-primary w-100 mt-3">Nueva reserva</a>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">Reservas</div>
            <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead><tr><th>#</th><th>Entrada</th><th>Salida</th><th>Estado</th><th class="text-end">Total</th><th></th></tr></thead>
                <tbody>
                    @forelse ($reservas as $r)
                        <tr>
                            <td>{{ $r->ReservaId }}</td>
                            <td>{{ $r->FechaEntrada->format('d/m/Y') }}</td>
                            <td>{{ $r->FechaSalida->format('d/m/Y') }}</td>
                            <td>@include('admin.parts.estado', ['estado' => $r->Estado])</td>
                            <td class="text-end">₡{{ number_format($r->Total, 0, ',', '.') }}</td>
                            <td class="text-end"><a href="{{ route('admin.reservas.show', $r->ReservaId) }}" class="btn btn-sm btn-outline-dark">Abrir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted">Sin reservas.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
@endsection

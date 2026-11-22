@extends('layouts.admin')
@section('titulo', 'Auditoría')

@section('contenido')

<form class="d-flex flex-wrap gap-2 align-items-end mb-3" method="GET">
    <div>
        <label class="form-label" for="tabla">Tabla</label>
        <select id="tabla" name="tabla" class="form-select">
            <option value="">Todas</option>
            @foreach ($tablas as $t)
                <option value="{{ $t }}" @selected(request('tabla') === $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div><label class="form-label" for="desde">Desde</label><input type="date" id="desde" name="desde" value="{{ $desde }}" class="form-control"></div>
    <div><label class="form-label" for="hasta">Hasta</label><input type="date" id="hasta" name="hasta" value="{{ $hasta }}" class="form-control"></div>
    <button class="btn btn-primary">Filtrar</button>
</form>

@php
    $operaciones = ['I' => 'Insertó', 'U' => 'Modificó', 'D' => 'Eliminó'];
@endphp

<div class="card">
    <div class="card-body">
        <table class="table table-hover align-middle datatable w-100" data-order='[[0, "desc"]]'>
            <thead>
                <tr><th>#</th><th>Fecha</th><th>Usuario</th><th>Tabla</th><th>Acción</th><th class="no-sort">Detalle</th></tr>
            </thead>
            <tbody>
                @foreach ($registros as $a)
                    @php $d = $a->detalle; @endphp
                    <tr>
                        <td>{{ $a->AuditoriaId }}</td>
                        <td data-order="{{ $a->Fecha->format('Y-m-d H:i:s') }}">{{ $a->Fecha->format('d/m/Y H:i') }}</td>
                        <td>{{ $a->Usuario }}</td>
                        <td>{{ $a->Tabla }}</td>
                        <td>{{ $operaciones[$a->Operacion] ?? $a->Operacion }}</td>
                        <td>
                            {{-- Resumen legible según la tabla --}}
                            @if ($a->Tabla === 'Reserva' && isset($d['EstadoNuevo']))
                                Reserva <a href="{{ route('admin.reservas.show', $d['ReservaId']) }}">#{{ $d['ReservaId'] }}</a>:
                                @include('admin.parts.estado', ['estado' => $d['EstadoAnterior']])
                                →
                                @include('admin.parts.estado', ['estado' => $d['EstadoNuevo']])
                            @elseif ($a->Tabla === 'Pago' && isset($d['Monto']))
                                {{ $d['Tipo'] ?? '' }} de ₡{{ number_format($d['Monto'], 0, ',', '.') }}
                                ({{ $d['Metodo'] ?? '' }}) en la reserva
                                <a href="{{ route('admin.reservas.show', $d['ReservaId']) }}">#{{ $d['ReservaId'] }}</a>
                            @endif

                            {{-- El JSON original, tal como lo guardó el trigger --}}
                            <details class="small mt-1">
                                <summary class="text-muted">JSON</summary>
                                <pre class="mb-0">{{ json_encode($d, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </details>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

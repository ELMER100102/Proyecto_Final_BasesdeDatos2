@extends('layouts.admin')
@section('titulo', 'Respaldos')

@section('contenido')
<div class="card mb-4" style="max-width: 760px;">
    <div class="card-body">
        <p class="text-muted mb-3">
            Esta sección permite crear respaldos los datos y descargarlos.
            Los respaldos se guardan en la carpeta <code>{{ config('respaldos.carpeta') }}</code>.
            El historial de respaldos se guarda en MongoDB (colección <code>respaldos</code>).

        <div class="d-flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.respaldos.sql') }}"
                  onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').innerText = 'Respaldando…';">
                @csrf
                <button class="btn btn-primary">Respaldar base de datos</button>
            </form>

            @if (session('respaldo_nuevo'))
                <a href="{{ route('admin.respaldos.descargar', session('respaldo_nuevo')) }}" class="btn btn-outline-dark">
                    Descargar el respaldo recién creado
                </a>
            @endif
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">Historial <span class="text-muted small">· guardado en MongoDB (colección respaldos)</span></div>
    <div class="card-body">
        <table class="table table-hover align-middle datatable w-100" data-order='[[0, "desc"]]'>
            <thead>
                <tr><th>Fecha</th><th>Usuario</th><th>Estado</th><th>Archivo</th><th class="text-end">Tamaño</th><th class="no-sort"></th></tr>
            </thead>
            <tbody>
                @foreach ($historial as $r)
                    <tr>
                        <td data-order="{{ $r->fecha?->format('Y-m-d H:i:s') }}">{{ $r->fecha?->format('d/m/Y H:i') }}</td>
                        <td>{{ $r->usuario }}</td>
                        <td>
                            @if ($r->estado === 'OK')
                                <span class="badge text-bg-success">OK</span>
                            @else
                                <span class="badge text-bg-danger">Error</span>
                            @endif
                        </td>
                        <td class="small">
                            @if ($r->estado === 'OK')
                                {{ $r->archivo }}
                            @else
                                <span class="text-danger">{{ $r->error }}</span>
                            @endif
                        </td>
                        <td class="text-end small" data-order="{{ $r->bytes ?? 0 }}">
                            {{ $r->bytes ? number_format($r->bytes / 1048576, 1) . ' MB' : '—' }}
                        </td>
                        <td class="text-end">
                            @if ($r->estado === 'OK' && $servicio->descargable($r->ruta))
                                <a href="{{ route('admin.respaldos.descargar', $r->id) }}" class="btn btn-sm btn-outline-dark">Descargar</a>
                            @elseif ($r->estado === 'OK')
                                <span class="text-muted small">No disponible</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

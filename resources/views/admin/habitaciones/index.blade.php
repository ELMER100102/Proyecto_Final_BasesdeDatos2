@extends('layouts.admin')
@section('titulo', 'Habitaciones')

@if (auth()->user()->esAdmin())
    @section('acciones')
        <a href="{{ route('admin.habitaciones.create') }}" class="btn btn-primary">Nueva habitación</a>
    @endsection
@endif

@section('contenido')
    @php $esAdmin = auth()->user()->esAdmin(); @endphp


    <div class="card">
        <table class="table table-hover mb-0 align-middle datatable">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Piso</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th class="no-sort">Cambiar estado</th>
                    @if ($esAdmin)
                        <th class="no-sort"></th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($habitaciones as $h)
                    <tr>
                        <td class="fw-semibold">{{ $h->Numero }}</td>
                        <td>{{ $h->Piso }}</td>
                        <td>{{ $h->tipo->Nombre }}</td>
                        <td>@include('admin.parts.estado', ['estado' => $h->Estado])</td>
                        <td>
                            @if ($h->Estado !== 'OCUPADA')
                                <form method="POST" action="{{ route('admin.habitaciones.estado', $h) }}"
                                    class="d-flex gap-1">
                                    @csrf
                                    @foreach (['DISPONIBLE' => 'Lista', 'SUCIA' => 'Sucia', 'MANTENIMIENTO' => 'Mantenimiento'] as $valor => $texto)
                                        @if ($h->Estado !== $valor)
                                            <button name="Estado" value="{{ $valor }}"
                                                class="btn btn-sm btn-outline-secondary">{{ $texto }}</button>
                                        @endif
                                    @endforeach
                                </form>
                            @else
                                <span class="text-muted small">Cambia con el check-out</span>
                            @endif
                        </td>
                        @if ($esAdmin)
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.habitaciones.edit', $h) }}"
                                    class="btn btn-sm btn-outline-dark">Editar</a>
                                @include('admin.parts.eliminar', [
                                    'ruta' => route('admin.habitaciones.destroy', $h),
                                    'texto' => 'Eliminar',
                                ])
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

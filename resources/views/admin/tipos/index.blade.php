@extends('layouts.admin')
@section('titulo', 'Tipos de habitación')

@section('acciones')
    <a href="{{ route('admin.tipos.create') }}" class="btn btn-primary">Nuevo tipo</a>
@endsection

@section('contenido')
<div class="card">
    <table class="table table-hover mb-0 align-middle datatable">
        <thead><tr><th>Nombre</th><th>Capacidad</th><th class="text-end">Precio por noche</th><th>Habitaciones</th><th>Activo</th><th class="no-sort"></th></tr></thead>
        <tbody>
            @foreach ($tipos as $t)
                <tr>
                    <td class="fw-semibold">{{ $t->Nombre }}</td>
                    <td>{{ $t->Capacidad }}</td>
                    <td class="text-end">₡{{ number_format($t->PrecioNoche, 0, ',', '.') }}</td>
                    <td>{{ $t->habitaciones_count }}</td>
                    <td>{{ $t->Activo ? 'Sí' : 'No' }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.tipos.edit', $t) }}" class="btn btn-sm btn-outline-dark">Editar</a>
                        @include('admin.parts.eliminar', ['ruta' => route('admin.tipos.destroy', $t)])
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

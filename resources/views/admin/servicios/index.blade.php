@extends('layouts.admin')
@section('titulo', 'Servicios')

@section('acciones')
    <a href="{{ route('admin.servicios.create') }}" class="btn btn-primary">Nuevo servicio</a>
@endsection

@section('contenido')
    <div class="card" style="max-width: 760px;">
        <table class="table table-hover mb-0 align-middle datatable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th class="text-end">Precio</th>
                    <th>Activo</th>
                    <th class="no-sort"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servicios as $s)
                    <tr>
                        <td>{{ $s->Nombre }}</td>
                        <td class="text-end">₡{{ number_format($s->Precio, 0, ',', '.') }}</td>
                        <td>{{ $s->Activo ? 'Sí' : 'No' }}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.servicios.edit', $s) }}"
                                class="btn btn-sm btn-outline-dark">Editar</a>
                            @include('admin.parts.eliminar', [
                                'ruta' => route('admin.servicios.destroy', $s),
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@extends('layouts.admin')
@section('titulo', 'Usuarios')

@section('contenido')
    <div class="d-flex flex-wrap gap-2 justify-content-between mb-3">
        <form class="d-flex gap-2" method="GET">
            <select name="rol" class="form-select w-auto" aria-label="Filtrar por rol" onchange="this.form.submit()">
                <option value="">Todos los roles</option>
                @foreach (['ADMINISTRADOR', 'RECEPCIONISTA', 'HUESPED'] as $rol)
                    <option value="{{ $rol }}" @selected(request('rol') === $rol)>{{ $rol }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">Nuevo empleado</a>
    </div>
    <div class="card">
        <table class="table table-hover mb-0 align-middle datatable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Activo</th>
                    <th class="no-sort"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $u)
                    <tr>
                        <td>{{ $u->persona->nombre_completo }}</td>
                        <td>{{ $u->persona->Correo }}</td>
                        <td>{{ $u->Rol }}</td>
                        <td>{{ $u->Activo ? 'Sí' : 'No' }}</td>
                        <td class="text-end"><a href="{{ route('admin.usuarios.edit', $u) }}"
                                class="btn btn-sm btn-outline-dark">Editar</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

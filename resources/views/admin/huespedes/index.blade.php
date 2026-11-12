@extends('layouts.admin')
@section('titulo', 'Huéspedes')

@section('acciones')
    <a href="{{ route('admin.huespedes.create') }}" class="btn btn-primary">Registrar huésped</a>
@endsection

@section('contenido')

    <div class="card">
        <table class="table table-hover mb-0 align-middle datatable">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Nacionalidad</th>
                    <th>Reservas</th>
                    <th class="no-sort"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($huespedes as $h)
                    <tr>
                        <td>{{ $h->persona->nombre_completo }}</td>
                        <td>{{ $h->persona->Correo }}</td>
                        <td>{{ $h->Nacionalidad }}</td>
                        <td>{{ $h->reservas_count }}</td>
                        <td class="text-end"><a href="{{ route('admin.huespedes.show', $h->PersonaId) }}"
                                class="btn btn-sm btn-outline-dark">Ver</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

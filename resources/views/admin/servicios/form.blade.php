@extends('layouts.admin')
@section('titulo', $servicio->exists ? 'Editar servicio' : 'Nuevo servicio')

@section('contenido')
    <div class="card" style="max-width: 520px;">
        <div class="card-body">
            <form method="POST"
                action="{{ $servicio->exists ? route('admin.servicios.update', $servicio) : route('admin.servicios.store') }}"
                class="row g-3">
                @csrf
                @if ($servicio->exists)
                    @method('PUT')
                @endif
                <div class="col-12"><label class="form-label" for="Nombre">Nombre</label><input id="Nombre" name="Nombre"
                        value="{{ old('Nombre', $servicio->Nombre) }}" class="form-control" required></div>
                <div class="col-12"><label class="form-label" for="Precio">Precio (₡)</label><input type="number"
                        id="Precio" name="Precio" value="{{ old('Precio', $servicio->Precio) }}" min="1"
                        step="0.01" class="form-control" required></div>
                <div class="col-12 form-check ms-1">
                    <input type="checkbox" class="form-check-input" id="Activo" name="Activo" value="1"
                        @checked(old('Activo', $servicio->Activo))>
                    <label class="form-check-label" for="Activo">Activo</label>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.servicios.index') }}" class="btn btn-link">Volver</a>
                </div>
            </form>
        </div>
    </div>
@endsection

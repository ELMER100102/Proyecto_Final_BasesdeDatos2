@extends('layouts.admin')
@section('titulo', $tipo->exists ? 'Editar ' . $tipo->Nombre : 'Nuevo tipo de habitación')

@section('contenido')
    <div class="card" style="max-width: 640px;">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data"
                action="{{ $tipo->exists ? route('admin.tipos.update', $tipo) : route('admin.tipos.store') }}"
                class="row g-3">
                @csrf
                @if ($tipo->exists)
                    @method('PUT')
                @endif
                <div class="col-12"><label class="form-label" for="Nombre">Nombre</label><input id="Nombre" name="Nombre"
                        value="{{ old('Nombre', $tipo->Nombre) }}" class="form-control" required></div>
                <div class="col-12"><label class="form-label" for="Descripcion">Descripción</label>
                    <textarea id="Descripcion" name="Descripcion" rows="3" class="form-control">{{ old('Descripcion', $tipo->Descripcion) }}</textarea>
                </div>
                <div class="col-md-6"><label class="form-label" for="Capacidad">Capacidad (personas)</label><input
                        type="number" id="Capacidad" name="Capacidad" value="{{ old('Capacidad', $tipo->Capacidad) }}"
                        min="1" max="12" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label" for="PrecioNoche">Precio por noche (₡)</label><input
                        type="number" id="PrecioNoche" name="PrecioNoche"
                        value="{{ old('PrecioNoche', $tipo->PrecioNoche) }}" min="1" step="0.01"
                        class="form-control" required></div>
                <div class="col-12">
                    <label class="form-label" for="Foto">Foto</label>
                    @if ($tipo->FotoUrl)
                        <div class="mb-2"><img src="{{ asset($tipo->FotoUrl) }}" alt="" style="max-height:120px">
                        </div>
                    @endif
                    <input type="file" id="Foto" name="Foto" accept="image/*" class="form-control">
                </div>
                <div class="col-12 form-check ms-1">
                    <input type="checkbox" class="form-check-input" id="Activo" name="Activo" value="1"
                        @checked(old('Activo', $tipo->Activo))>
                    <label class="form-check-label" for="Activo">Activo (se muestra en el sitio)</label>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.tipos.index') }}" class="btn btn-link">Volver</a>
                </div>
            </form>
        </div>
    </div>
@endsection

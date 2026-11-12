@extends('layouts.admin')
@section('titulo', $imagen->exists ? 'Editar imagen' : 'Agregar imagen')

@section('contenido')
    <div class="card" style="max-width: 560px;">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data"
                action="{{ $imagen->exists ? route('admin.imagenes.update', $imagen) : route('admin.imagenes.store') }}"
                class="row g-3">
                @csrf
                @if ($imagen->exists)
                    @method('PUT')
                @endif
                <div class="col-md-6">
                    <label class="form-label" for="Tipo">Dónde se muestra</label>
                    <select id="Tipo" name="Tipo" class="form-select">
                        <option value="SLIDE" @selected(old('Tipo', $imagen->Tipo) === 'SLIDE')>Carrusel del inicio</option>
                        <option value="GALERIA" @selected(old('Tipo', $imagen->Tipo) === 'GALERIA')>Galería</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label" for="Orden">Orden</label><input type="number"
                        id="Orden" name="Orden" value="{{ old('Orden', $imagen->Orden) }}" min="1"
                        class="form-control" required></div>
                <div class="col-12"><label class="form-label" for="Titulo">Título</label><input id="Titulo"
                        name="Titulo" value="{{ old('Titulo', $imagen->Titulo) }}" class="form-control"></div>
                <div class="col-12">
                    <label class="form-label" for="Imagen">Archivo de imagen</label>
                    @if ($imagen->ImagenUrl)
                        <div class="mb-2"><img src="{{ asset($imagen->ImagenUrl) }}" alt=""
                                style="max-height:120px"></div>
                    @endif
                    <input type="file" id="Imagen" name="Imagen" accept="image/*" class="form-control"
                        @required(!$imagen->exists)>
                </div>
                <div class="col-12 form-check ms-1">
                    <input type="checkbox" class="form-check-input" id="Activo" name="Activo" value="1"
                        @checked(old('Activo', $imagen->Activo))>
                    <label class="form-check-label" for="Activo">Visible en el sitio</label>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary">Guardar</button>
                    <a href="{{ route('admin.imagenes.index') }}" class="btn btn-link">Volver</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('layouts.admin')
@section('titulo', 'Datos del hotel')

@section('contenido')
    <div class="card" style="max-width: 760px;">
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data" action="{{ route('admin.hotel.update') }}" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-8"><label class="form-label" for="Nombre">Nombre</label><input id="Nombre"
                        name="Nombre" value="{{ old('Nombre', $hotel->Nombre) }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label" for="PorcentajeImpuesto">Impuesto (%)</label><input
                        type="number" id="PorcentajeImpuesto" name="PorcentajeImpuesto"
                        value="{{ old('PorcentajeImpuesto', $hotel->PorcentajeImpuesto) }}" step="0.01" min="0"
                        max="100" class="form-control" required></div>
                <div class="col-12"><label class="form-label" for="Descripcion">Descripción</label>
                    <textarea id="Descripcion" name="Descripcion" rows="3" class="form-control">{{ old('Descripcion', $hotel->Descripcion) }}</textarea>
                </div>
                <div class="col-12"><label class="form-label" for="Direccion">Dirección</label><input id="Direccion"
                        name="Direccion" value="{{ old('Direccion', $hotel->Direccion) }}" class="form-control" required>
                </div>
                <div class="col-md-6"><label class="form-label" for="Latitud">Latitud (mapa)</label><input id="Latitud"
                        name="Latitud" value="{{ old('Latitud', $hotel->Latitud) }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label" for="Longitud">Longitud (mapa)</label><input id="Longitud"
                        name="Longitud" value="{{ old('Longitud', $hotel->Longitud) }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label" for="Telefono">Teléfono</label><input id="Telefono"
                        name="Telefono" value="{{ old('Telefono', $hotel->Telefono) }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label" for="Correo">Correo</label><input type="email"
                        id="Correo" name="Correo" value="{{ old('Correo', $hotel->Correo) }}" class="form-control"
                        required></div>
                <div class="col-12">
                    <label class="form-label" for="Logo">Logo</label>
                    @if ($hotel->LogoUrl)
                        <div class="mb-2"><img src="{{ asset($hotel->LogoUrl) }}" alt="" style="max-height:60px">
                        </div>
                    @endif
                    <input type="file" id="Logo" name="Logo" accept="image/*" class="form-control">
                </div>
                <div class="col-12"><button class="btn btn-primary">Guardar cambios</button></div>
            </form>
        </div>
    </div>
@endsection

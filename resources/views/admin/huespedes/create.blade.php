@extends('layouts.admin')
@section('titulo', 'Registrar huésped')

@section('contenido')
<div class="card" style="max-width: 760px;">
    <div class="card-body">
        <p class="text-muted">La cédula y el teléfono se guardan encriptados en SQL Server (sp_RegistrarPersona).</p>
        <form method="POST" action="{{ route('admin.huespedes.store') }}" class="row g-3">
            @csrf
            <div class="col-md-6"><label class="form-label" for="Nombre">Nombre</label><input id="Nombre" name="Nombre" value="{{ old('Nombre') }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label" for="Apellidos">Apellidos</label><input id="Apellidos" name="Apellidos" value="{{ old('Apellidos') }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label" for="Cedula">Cédula o pasaporte</label><input id="Cedula" name="Cedula" value="{{ old('Cedula') }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label" for="Nacionalidad">Nacionalidad</label><input id="Nacionalidad" name="Nacionalidad" value="{{ old('Nacionalidad', 'Costa Rica') }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label" for="Correo">Correo</label><input type="email" id="Correo" name="Correo" value="{{ old('Correo') }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label" for="Telefono">Teléfono</label><input id="Telefono" name="Telefono" value="{{ old('Telefono') }}" class="form-control"></div>
            <div class="col-12">
                <button class="btn btn-primary">Registrar</button>
                <a href="{{ route('admin.huespedes.index') }}" class="btn btn-link">Volver</a>
            </div>
        </form>
    </div>
</div>
@endsection

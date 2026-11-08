@extends('layouts.web')
@section('titulo', 'Registrarse')

@section('contenido')
<div class="container" style="max-width: 640px;">
    <h1 class="h3 my-4">Crear cuenta</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('registro') }}" class="row g-3">
        @csrf
        <div class="col-md-6">
            <label for="nombre" class="form-label">Nombre</label>
            <input id="nombre" name="nombre" value="{{ old('nombre') }}" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input id="apellidos" name="apellidos" value="{{ old('apellidos') }}" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="cedula" class="form-label">Cédula o pasaporte</label>
            <input id="cedula" name="cedula" value="{{ old('cedula') }}" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="nacionalidad" class="form-label">Nacionalidad</label>
            <input id="nacionalidad" name="nacionalidad" value="{{ old('nacionalidad', 'Costa Rica') }}" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" name="correo" value="{{ old('correo') }}" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="telefono" class="form-label">Teléfono</label>
            <input id="telefono" name="telefono" value="{{ old('telefono') }}" class="form-control">
        </div>
        <div class="col-md-6">
            <label for="contrasena" class="form-label">Contraseña (mínimo 8 caracteres)</label>
            <input type="password" id="contrasena" name="contrasena" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label for="contrasena_confirmation" class="form-label">Repita la contraseña</label>
            <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" class="form-control" required>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Crear cuenta</button>
        </div>
    </form>
</div>
@endsection

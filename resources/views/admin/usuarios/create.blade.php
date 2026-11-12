@extends('layouts.admin')
@section('titulo', 'Nuevo empleado')

@section('contenido')
    <div class="card" style="max-width: 760px;">
        <div class="card-body">
            <p class="text-muted">Se crea la persona (cédula y teléfono encriptados), el empleado y su usuario con
                sp_RegistrarPersona.</p>
            <form method="POST" action="{{ route('admin.usuarios.store') }}" class="row g-3">
                @csrf
                <div class="col-md-6"><label class="form-label" for="Nombre">Nombre</label><input id="Nombre"
                        name="Nombre" value="{{ old('Nombre') }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label" for="Apellidos">Apellidos</label><input id="Apellidos"
                        name="Apellidos" value="{{ old('Apellidos') }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label" for="Cedula">Cédula</label><input id="Cedula"
                        name="Cedula" value="{{ old('Cedula') }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label" for="Telefono">Teléfono</label><input id="Telefono"
                        name="Telefono" value="{{ old('Telefono') }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label" for="Correo">Correo (usuario para entrar)</label><input
                        type="email" id="Correo" name="Correo" value="{{ old('Correo') }}" class="form-control"
                        required></div>
                <div class="col-md-6"><label class="form-label" for="Contrasena">Contraseña</label><input type="password"
                        id="Contrasena" name="Contrasena" minlength="8" class="form-control" required></div>
                <div class="col-md-6">
                    <label class="form-label" for="Puesto">Puesto</label>
                    <select id="Puesto" name="Puesto" class="form-select">
                        <option value="RECEPCIONISTA" @selected(old('Puesto') === 'RECEPCIONISTA')>Recepcionista</option>
                        <option value="ADMINISTRADOR" @selected(old('Puesto') === 'ADMINISTRADOR')>Administrador</option>
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label" for="Salario">Salario (₡)</label><input type="number"
                        id="Salario" name="Salario" value="{{ old('Salario') }}" min="1" class="form-control"
                        required></div>
                <div class="col-12">
                    <button class="btn btn-primary">Crear empleado</button>
                    <a href="{{ route('admin.usuarios.index') }}" class="btn btn-link">Volver</a>
                </div>
            </form>
        </div>
    </div>
@endsection

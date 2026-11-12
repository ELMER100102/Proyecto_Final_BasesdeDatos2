@extends('layouts.admin')
@section('titulo', 'Editar usuario')

@section('contenido')
<div class="card" style="max-width: 520px;">
    <div class="card-body">
        <p class="mb-3"><strong>{{ $usuario->persona->nombre_completo }}</strong><br><span class="text-muted">{{ $usuario->persona->Correo }}</span></p>
        <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="row g-3">
            @csrf
            @method('PUT')
            <div class="col-12">
                <label class="form-label" for="Rol">Rol</label>
                <select id="Rol" name="Rol" class="form-select">
                    @foreach (['ADMINISTRADOR', 'RECEPCIONISTA', 'HUESPED'] as $rol)
                        <option value="{{ $rol }}" @selected(old('Rol', $usuario->Rol) === $rol)>{{ $rol }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12"><label class="form-label" for="Contrasena">Nueva contraseña (dejar vacío para no cambiarla)</label><input type="password" id="Contrasena" name="Contrasena" minlength="8" class="form-control"></div>
            <div class="col-12 form-check ms-1">
                <input type="checkbox" class="form-check-input" id="Activo" name="Activo" value="1" @checked(old('Activo', $usuario->Activo))>
                <label class="form-check-label" for="Activo">Activo (puede iniciar sesión)</label>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-link">Volver</a>
            </div>
        </form>
    </div>
</div>
@endsection

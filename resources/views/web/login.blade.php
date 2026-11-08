@extends('layouts.web')
@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="container" style="max-width: 420px;">
    <h1 class="h3 my-4">Iniciar sesión</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="email" id="correo" name="correo" value="{{ old('correo') }}"
                   class="form-control @error('correo') is-invalid @enderror" required autofocus>
            @error('correo') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="mb-3">
            <label for="contrasena" class="form-label">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" class="form-control" required>
        </div>
        <button class="btn btn-primary w-100">Entrar</button>
    </form>

    <p class="mt-3 text-center">¿No tiene cuenta? <a href="{{ route('registro') }}">Regístrese</a></p>
</div>
@endsection

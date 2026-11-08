@extends('layouts.web')
@section('titulo', 'Registrarse')

@section('contenido')
<section class="seccion">
    <div class="contenedor contenedor--angosto">
        <h1 class="pagina__titulo">Crear cuenta</h1>

        <form class="formulario formulario--dos-columnas" method="POST" action="{{ route('registro') }}">
            @csrf
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input id="nombre" name="nombre" value="{{ old('nombre') }}" required>
            </div>
            <div class="campo">
                <label for="apellidos">Apellidos</label>
                <input id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required>
            </div>
            <div class="campo">
                <label for="cedula">Cédula o pasaporte</label>
                <input id="cedula" name="cedula" value="{{ old('cedula') }}" required>
            </div>
            <div class="campo">
                <label for="nacionalidad">Nacionalidad</label>
                <input id="nacionalidad" name="nacionalidad" value="{{ old('nacionalidad', 'Costa Rica') }}" required>
            </div>
            <div class="campo">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required>
            </div>
            <div class="campo">
                <label for="telefono">Teléfono (opcional)</label>
                <input id="telefono" name="telefono" value="{{ old('telefono') }}">
            </div>
            <div class="campo">
                <label for="contrasena">Contraseña (mínimo 8 caracteres)</label>
                <input type="password" id="contrasena" name="contrasena" minlength="8" required>
            </div>
            <div class="campo">
                <label for="contrasena_confirmation">Repita la contraseña</label>
                <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" minlength="8" required>
            </div>
            <button class="boton boton--primario">Crear cuenta</button>
        </form>

        <p class="nota">¿Ya tiene cuenta? <a class="enlace" href="{{ route('login') }}">Inicie sesión</a></p>
    </div>
</section>
@endsection

@extends('layouts.web')
@section('titulo', 'Iniciar sesión')

@section('contenido')
<section class="seccion">
    <div class="contenedor contenedor--angosto">
        <h1 class="pagina__titulo">Iniciar sesión</h1>

        <form class="formulario" method="POST" action="{{ route('login') }}">
            @csrf
            <div class="campo">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required autofocus>
            </div>
            <div class="campo">
                <label for="contrasena">Contraseña</label>
                <input type="password" id="contrasena" name="contrasena" required>
            </div>
            <button class="boton boton--primario boton--ancho">Entrar</button>
        </form>

        <p class="nota">¿No tiene cuenta? <a class="enlace" href="{{ route('registro') }}">Regístrese</a></p>
    </div>
</section>
@endsection

@extends('layouts.web')
@section('titulo', 'Registrarse')

@section('contenido')
<section class="seccion">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <h1 class="titulo-pagina text-center">Crear cuenta</h1>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('registro') }}" class="row g-3">
                            @csrf
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="nombre">Nombre</label>
                                <input class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" required autocomplete="given-name">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="apellidos">Apellidos</label>
                                <input class="form-control" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" required autocomplete="family-name">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="cedula">Cédula o pasaporte</label>
                                <input class="form-control" id="cedula" name="cedula" value="{{ old('cedula') }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="nacionalidad">Nacionalidad</label>
                                <input class="form-control" id="nacionalidad" name="nacionalidad" value="{{ old('nacionalidad', 'Costa Rica') }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="correo">Correo</label>
                                <input class="form-control" type="email" id="correo" name="correo" value="{{ old('correo') }}" required autocomplete="email">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="telefono">Teléfono (opcional)</label>
                                <input class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" autocomplete="tel">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="contrasena">Contraseña (mín. 8 caracteres)</label>
                                <input class="form-control" type="password" id="contrasena" name="contrasena" minlength="8" required autocomplete="new-password">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="contrasena_confirmation">Repita la contraseña</label>
                                <input class="form-control" type="password" id="contrasena_confirmation" name="contrasena_confirmation" minlength="8" required autocomplete="new-password">
                            </div>
                            <div class="col-12 pt-2">
                                <button class="btn btn-primary w-100">Crear cuenta</button>
                            </div>
                        </form>
                    </div>
                </div>

                <p class="nota text-center mt-4 mx-auto">¿Ya tiene cuenta? <a href="{{ route('login') }}">Inicie sesión</a></p>
            </div>
        </div>
    </div>
</section>
@endsection

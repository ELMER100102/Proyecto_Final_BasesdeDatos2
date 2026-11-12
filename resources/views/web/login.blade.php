@extends('layouts.web')
@section('titulo', 'Iniciar sesión')

@section('contenido')
<section class="seccion">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <h1 class="titulo-pagina text-center">Iniciar sesión</h1>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="correo">Correo</label>
                                <input class="form-control" type="email" id="correo" name="correo" value="{{ old('correo') }}" required autofocus autocomplete="email">
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="contrasena">Contraseña</label>
                                <input class="form-control" type="password" id="contrasena" name="contrasena" required autocomplete="current-password">
                            </div>
                            <button class="btn btn-primary w-100">Entrar</button>
                        </form>
                    </div>
                </div>

                <p class="nota text-center mt-4 mx-auto">¿No tiene cuenta? <a href="{{ route('registro') }}">Regístrese</a></p>
            </div>
        </div>
    </div>
</section>
@endsection

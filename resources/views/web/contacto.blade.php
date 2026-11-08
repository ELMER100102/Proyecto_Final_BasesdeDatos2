@extends('layouts.web')
@section('titulo', 'Contacto')

@section('contenido')
<section class="seccion">
    <div class="contenedor contacto">
        <div class="contacto__info">
            <h1 class="pagina__titulo">Contáctenos</h1>
            <p>{{ $hotelSitio->Direccion ?? '' }}</p>
            <p>Tel. {{ $hotelSitio->Telefono ?? '' }}</p>
            <p>{{ $hotelSitio->Correo ?? '' }}</p>
        </div>

        <form class="formulario" method="POST" action="{{ route('contacto.enviar') }}">
            @csrf
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input id="nombre" name="nombre" value="{{ old('nombre', auth()->user()?->persona?->nombre_completo) }}" required>
            </div>
            <div class="campo">
                <label for="correo">Correo</label>
                <input type="email" id="correo" name="correo" value="{{ old('correo', auth()->user()?->persona?->Correo) }}" required>
            </div>
            <div class="campo">
                <label for="telefono">Teléfono (opcional)</label>
                <input id="telefono" name="telefono" value="{{ old('telefono') }}">
            </div>
            <div class="campo">
                <label for="asunto">Asunto</label>
                <input id="asunto" name="asunto" value="{{ old('asunto') }}" required>
            </div>
            <div class="campo">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="5" required>{{ old('mensaje') }}</textarea>
            </div>
            <button class="boton boton--primario">Enviar mensaje</button>
        </form>
    </div>
</section>
@endsection

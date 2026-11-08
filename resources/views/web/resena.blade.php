@extends('layouts.web')
@section('titulo', 'Dejar reseña')

@section('contenido')
<section class="seccion">
    <div class="contenedor contenedor--angosto">
        <h1 class="pagina__titulo">¿Cómo fue su estancia?</h1>
        <p>
            Reserva #{{ $reserva->ReservaId }} ·
            {{ $reserva->detalles->first()?->habitacion?->tipo?->Nombre }} ·
            {{ $reserva->FechaEntrada->format('d/m/Y') }} al {{ $reserva->FechaSalida->format('d/m/Y') }}
        </p>

        <form class="formulario" method="POST" action="{{ route('resena.guardar', $reserva->ReservaId) }}">
            @csrf
            <fieldset class="campo">
                <legend>Calificación</legend>
                <div class="calificacion">
                    @for ($i = 5; $i >= 1; $i--)
                        <label class="calificacion__opcion">
                            <input type="radio" name="calificacion" value="{{ $i }}" @checked(old('calificacion', 5) == $i) required>
                            {{ $i }} {{ $i == 1 ? 'estrella' : 'estrellas' }}
                        </label>
                    @endfor
                </div>
            </fieldset>
            <div class="campo">
                <label for="comentario">Comentario</label>
                <textarea id="comentario" name="comentario" rows="5" minlength="10" maxlength="1000" required>{{ old('comentario') }}</textarea>
            </div>
            <button class="boton boton--primario">Publicar reseña</button>
        </form>
    </div>
</section>
@endsection

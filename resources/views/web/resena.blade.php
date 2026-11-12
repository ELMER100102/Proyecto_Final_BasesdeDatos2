@extends('layouts.web')
@section('titulo', 'Dejar reseña')

@section('contenido')
<section class="seccion">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <span class="eyebrow">Reseña</span>
                <h1 class="titulo-pagina">¿Cómo fue su estancia?</h1>
                <p>
                    Reserva #{{ $reserva->ReservaId }} ·
                    {{ $reserva->detalles->first()?->habitacion?->tipo?->Nombre }} ·
                    {{ $reserva->FechaEntrada->format('d/m/Y') }} al {{ $reserva->FechaSalida->format('d/m/Y') }}
                </p>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('resena.guardar', $reserva->ReservaId) }}">
                            @csrf
                            <fieldset class="mb-4">
                                <legend class="form-titulo">Calificación</legend>
                                <div class="calificacion d-flex flex-wrap gap-2">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <input type="radio" class="btn-check" name="calificacion" id="calificacion-{{ $i }}" value="{{ $i }}" autocomplete="off" @checked(old('calificacion', 5) == $i) required>
                                        <label class="btn btn-outline-dark" for="calificacion-{{ $i }}">
                                            {{ $i }} {{ $i == 1 ? 'estrella' : 'estrellas' }}
                                        </label>
                                    @endfor
                                </div>
                            </fieldset>
                            <div class="mb-4">
                                <label class="form-label" for="comentario">Comentario</label>
                                <textarea class="form-control" id="comentario" name="comentario" rows="5" minlength="10" maxlength="1000" required>{{ old('comentario') }}</textarea>
                            </div>
                            <button class="btn btn-primary">Publicar reseña</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

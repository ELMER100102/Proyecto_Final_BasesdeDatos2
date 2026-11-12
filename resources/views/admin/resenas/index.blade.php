@extends('layouts.admin')
@section('titulo', 'Reseñas (MongoDB)')

@section('contenido')
    @forelse ($resenas as $r)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <strong>{{ $r->huesped }}</strong> · {{ $r->tipoHabitacion }} · reserva #{{ $r->reservaId }}
                        <div class="estrellas" aria-label="{{ (int) $r->calificacion }} de 5 estrellas">
                            {{ str_repeat('★', (int) $r->calificacion) }}{{ str_repeat('☆', 5 - (int) $r->calificacion) }}
                        </div>
                    </div>
                    <div class="text-muted small">{{ $r->fecha?->format('d/m/Y') }}</div>
                </div>
                <p class="my-2">{{ $r->comentario }}</p>

                <form method="POST" action="{{ route('admin.resenas.responder', $r->id) }}" class="d-flex flex-wrap flex-sm-nowrap gap-2">
                    @csrf
                    <input name="respuestaHotel" value="{{ $r->respuestaHotel }}" class="form-control"
                        placeholder="Respuesta del hotel (se muestra en el sitio)">
                    <button class="btn btn-outline-dark text-nowrap">Guardar respuesta</button>
                </form>
            </div>
            <div class="card-footer text-end">
                @include('admin.parts.eliminar', ['ruta' => route('admin.resenas.destroy', $r->id)])
            </div>
        </div>
    @empty
        <p class="text-muted">No hay reseñas.</p>
    @endforelse
@endsection

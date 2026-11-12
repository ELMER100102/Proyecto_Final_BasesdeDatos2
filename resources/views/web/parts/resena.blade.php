{{-- Reseña de MongoDB. Recibe $resena --}}
<article class="card h-100 resena">
    <div class="card-body">
        <p class="estrellas" role="img" aria-label="{{ $resena->calificacion }} de 5 estrellas">
            {{ str_repeat('★', (int) $resena->calificacion) }}{{ str_repeat('☆', 5 - (int) $resena->calificacion) }}
        </p>
        <blockquote>"{{ $resena->comentario }}"</blockquote>
        <p class="autor">{{ $resena->huesped }} · {{ $resena->tipoHabitacion }}</p>
        @if ($resena->respuestaHotel)
            <p class="respuesta"><strong>Respuesta del hotel:</strong> {{ $resena->respuestaHotel }}</p>
        @endif
    </div>
</article>

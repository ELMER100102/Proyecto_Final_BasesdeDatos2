{{-- Reseña de MongoDB. Recibe $resena --}}
<article class="tarjeta tarjeta--resena">
    <p class="estrellas" aria-label="{{ $resena->calificacion }} de 5">
        {{ str_repeat('★', (int) $resena->calificacion) }}{{ str_repeat('☆', 5 - (int) $resena->calificacion) }}
    </p>
    <blockquote class="tarjeta__texto">"{{ $resena->comentario }}"</blockquote>
    <p class="tarjeta__autor">{{ $resena->huesped }} · {{ $resena->tipoHabitacion }}</p>
    @if ($resena->respuestaHotel)
        <p class="tarjeta__respuesta"><strong>Respuesta del hotel:</strong> {{ $resena->respuestaHotel }}</p>
    @endif
</article>

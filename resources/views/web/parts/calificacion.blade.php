{{-- Calificación promedio de un tipo de habitación (MongoDB, aggregate).
     Uso: @include('web.partials.calificacion', ['nombre' => $tipo->Nombre]) --}}
@php $cal = app(\App\Services\CalificacionService::class)->promedios()[$nombre] ?? null; @endphp

@if ($cal)
    <p class="calificacion-promedio" aria-label="Calificación {{ $cal['promedio'] }} de 5">
        <span class="calificacion-promedio__estrella">★</span>
        <strong>{{ number_format($cal['promedio'], 1) }}</strong>
        <span class="calificacion-promedio__total">({{ $cal['total'] }} {{ $cal['total'] == 1 ? 'reseña' : 'reseñas' }})</span>
    </p>
@else
    <p class="calificacion-promedio calificacion-promedio--vacia">Sin reseñas todavía</p>
@endif

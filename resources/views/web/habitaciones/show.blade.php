@extends('layouts.web')
@section('titulo', $tipo->Nombre)

@section('contenido')
<section class="seccion">
    <div class="contenedor detalle">
        <div class="detalle__imagen">
            @if ($tipo->FotoUrl)
                <img src="{{ asset($tipo->FotoUrl) }}" alt="Habitación {{ $tipo->Nombre }}">
            @endif
        </div>

        <div class="detalle__info">
            <a class="enlace" href="{{ route('habitaciones') }}">← Todas las habitaciones</a>
            <h1 class="pagina__titulo">{{ $tipo->Nombre }}</h1>
            <p>{{ $tipo->Descripcion }}</p>
            <ul class="datos">
                <li>Hasta {{ $tipo->Capacidad }} personas</li>
            </ul>
            <p class="precio">
                <span class="precio__monto">₡{{ number_format($tipo->PrecioNoche, 0, ',', '.') }}</span>
                <span class="precio__unidad">por noche + impuesto</span>
            </p>

            {{-- Buscador con el tipo ya elegido --}}
            <form class="buscador buscador--vertical" method="GET" action="{{ route('reservar') }}">
                <div class="campo">
                    <label for="entrada">Llegada</label>
                    <input type="date" id="entrada" name="entrada" min="{{ now()->toDateString() }}" required>
                </div>
                <div class="campo">
                    <label for="salida">Salida</label>
                    <input type="date" id="salida" name="salida" min="{{ now()->addDay()->toDateString() }}" required>
                </div>
                <div class="campo">
                    <label for="personas">Personas</label>
                    <input type="number" id="personas" name="personas" value="1" min="1" max="{{ $tipo->Capacidad }}" required>
                </div>
                <button class="boton boton--primario">Ver disponibilidad</button>
            </form>
        </div>
    </div>
</section>

<section class="seccion seccion--alterna">
    <div class="contenedor">
        <h2 class="seccion__titulo">Reseñas de esta habitación</h2>
        @forelse ($resenas as $resena)
            @include('web.partials.resena', ['resena' => $resena])
        @empty
            <p class="vacio">Todavía no hay reseñas para esta habitación.</p>
        @endforelse
    </div>
</section>
@endsection

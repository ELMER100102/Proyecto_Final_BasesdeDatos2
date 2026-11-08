@extends('layouts.web')
@section('titulo', 'Inicio')

@section('contenido')

{{-- 1. PORTADA: primer slide como imagen principal + buscador rápido --}}
<section class="portada">
    @php $principal = $slides->first(); @endphp
    @if ($principal)
        <img class="portada__imagen" src="{{ asset($principal->ImagenUrl) }}" alt="">
    @endif
    <div class="contenedor portada__contenido">
        <h1 class="portada__titulo">{{ $principal->Titulo ?? $hotel->Nombre }}</h1>
        <p class="portada__texto">{{ $hotel->Descripcion }}</p>

        <form class="buscador" method="GET" action="{{ route('reservar') }}">
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
                <input type="number" id="personas" name="personas" value="2" min="1" max="10" required>
            </div>
            <button class="boton boton--primario">Ver disponibilidad</button>
        </form>
    </div>
</section>

{{-- 2. CARRUSEL: los demás slides --}}
@if ($slides->count() > 1)
    <section class="seccion">
        <div class="contenedor carrusel">
            @foreach ($slides as $slide)
                <figure class="carrusel__elemento">
                    <img src="{{ asset($slide->ImagenUrl) }}" alt="{{ $slide->Titulo }}">
                    <figcaption>{{ $slide->Titulo }}</figcaption>
                </figure>
            @endforeach
        </div>
    </section>
@endif

{{-- 3. HABITACIONES --}}
<section class="seccion">
    <div class="contenedor">
        <header class="seccion__encabezado">
            <h2 class="seccion__titulo">Nuestras habitaciones</h2>
            <a class="enlace" href="{{ route('habitaciones') }}">Ver todas</a>
        </header>
        <div class="rejilla">
            @foreach ($tipos as $tipo)
                @include('web.parts.tarjeta-habitacion', [
                    'tipo' => $tipo,
                    'accion' => new \Illuminate\Support\HtmlString(
                        '<a class="boton boton--secundario" href="' . route('habitaciones.show', $tipo->TipoHabitacionId) . '">Ver detalle</a>'
                    ),
                ])
            @endforeach
        </div>
    </div>
</section>

{{-- 4. GALERÍA --}}
@if ($galeria->isNotEmpty())
    <section class="seccion seccion--alterna">
        <div class="contenedor">
            <h2 class="seccion__titulo">Galería</h2>
            <div class="galeria">
                @foreach ($galeria as $imagen)
                    <figure class="galeria__elemento">
                        <img src="{{ asset($imagen->ImagenUrl) }}" alt="{{ $imagen->Titulo }}">
                        <figcaption>{{ $imagen->Titulo }}</figcaption>
                    </figure>
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- 5. RESEÑAS (MongoDB) --}}
@if ($resenas->isNotEmpty())
    <section class="seccion">
        <div class="contenedor">
            <h2 class="seccion__titulo">Lo que dicen nuestros huéspedes</h2>
            <div class="rejilla">
                @foreach ($resenas as $resena)
                    @include('web.parts.resena', ['resena' => $resena])
                @endforeach
            </div>
        </div>
    </section>
@endif

{{-- 6. UBICACIÓN (mapa con Leaflet + OpenStreetMap, sin llave de API) --}}
@if ($hotel->Latitud && $hotel->Longitud)
    <section class="seccion seccion--alterna">
        <div class="contenedor ubicacion">
            <div>
                <h2 class="seccion__titulo">Cómo llegar</h2>
                <p>{{ $hotel->Direccion }}</p>
                <p>Tel. {{ $hotel->Telefono }} · {{ $hotel->Correo }}</p>
            </div>
            <div id="mapa" class="mapa" data-lat="{{ $hotel->Latitud }}" data-lng="{{ $hotel->Longitud }}"></div>
        </div>
    </section>

    @push('estilos')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @endpush
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            const caja = document.getElementById('mapa');
            const punto = [parseFloat(caja.dataset.lat), parseFloat(caja.dataset.lng)];
            const mapa = L.map('mapa').setView(punto, 15);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(mapa);
            L.marker(punto).addTo(mapa).bindPopup(@json($hotel->Nombre));
        </script>
    @endpush
@endif

@endsection

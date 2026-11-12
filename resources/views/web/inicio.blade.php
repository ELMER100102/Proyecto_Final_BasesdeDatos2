@extends('layouts.web')
@section('titulo', 'Inicio')

@section('contenido')

    {{-- 1. PORTADA: primer slide como fondo + buscador rápido --}}
    <section class="portada">
        @php $principal = $slides->first(); @endphp
        @if ($principal)
            <img class="portada__imagen" src="{{ asset($principal->ImagenUrl) }}" alt="">
        @endif
        <div class="container portada__contenido">
            <h1 class="titulo-portada">{{ $principal->Titulo ?? $hotel->Nombre }}</h1>
            <p class="portada__texto">{{ $hotel->Descripcion }}</p>

            <div id="reserva-ancla" class="reserva-ancla">
                @include('web.parts.tarjeta-reservacion')
            </div>
        </div>
    </section>

    {{-- 2. CARRUSEL: todos los slides (sin avance automático) --}}
    @if ($slides->count() > 1)
        <section class="seccion pb-0">
            <div class="container">
                <div id="carruselHotel" class="carousel slide carrusel" data-bs-touch="true">
                    <div class="carousel-indicators">
                        @foreach ($slides->values() as $i => $slide)
                            <button type="button" data-bs-target="#carruselHotel" data-bs-slide-to="{{ $i }}"
                                class="{{ $i === 0 ? 'active' : '' }}"
                                @if ($i === 0) aria-current="true" @endif
                                aria-label="{{ $slide->Titulo }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach ($slides->values() as $i => $slide)
                            <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                <img src="{{ asset($slide->ImagenUrl) }}" class="d-block" alt="{{ $slide->Titulo }}">
                                <div class="carousel-caption">{{ $slide->Titulo }}</div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carruselHotel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carruselHotel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>
        </section>
    @endif

    {{-- 3. HABITACIONES --}}
    <section class="seccion">
        <div class="container">
            <header class="seccion-encabezado d-flex flex-wrap justify-content-between align-items-end gap-3">
                <div>
                    <span class="eyebrow">Habitaciones</span>
                    <h2 class="titulo-seccion mb-0">Nuestras habitaciones</h2>
                </div>
                <a class="btn btn-outline-dark" href="{{ route('habitaciones') }}">Ver todas</a>
            </header>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($tipos as $tipo)
                    <div class="col">
                        @include('web.parts.tarjeta-habitacion', [
                            'tipo' => $tipo,
                            'accion' => new \Illuminate\Support\HtmlString(
                                '<a class="btn btn-outline-dark w-100" href="' .
                                    route('habitaciones.show', $tipo->TipoHabitacionId) .
                                    '">Ver detalle</a>'),
                        ])
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- 4. GALERÍA --}}
    @if ($galeria->isNotEmpty())
        <section class="seccion pt-0">
            <div class="container">
                <header class="seccion-encabezado">
                    <span class="eyebrow">Galería</span>
                    <h2 class="titulo-seccion mb-0">Un vistazo al hotel</h2>
                </header>
                <div class="row g-3 galeria">
                    @foreach ($galeria as $imagen)
                        <figure class="col-6 col-md-4 col-lg-3 m-0">
                            <div class="marco">
                                <img src="{{ asset($imagen->ImagenUrl) }}" alt="{{ $imagen->Titulo }}" loading="lazy">
                            </div>
                            <figcaption class="pie-foto">{{ $imagen->Titulo }}</figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 5. RESEÑAS (MongoDB) --}}
    @if ($resenas->isNotEmpty())
        <section class="seccion pt-0">
            <div class="container">
                <header class="seccion-encabezado">
                    <span class="eyebrow">Reseñas</span>
                    <h2 class="titulo-seccion mb-0">Lo que dicen nuestros huéspedes</h2>
                </header>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @foreach ($resenas as $resena)
                        <div class="col">
                            @include('web.parts.resena', ['resena' => $resena])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- 6. UBICACIÓN (mapa con Leaflet + OpenStreetMap, sin llave de API) --}}
    @if ($hotel->Latitud && $hotel->Longitud)
        <section class="seccion pt-0">
            <div class="container">
                <div class="row g-4 g-lg-5 align-items-stretch">
                    <div class="col-12 col-lg-5">
                        <span class="eyebrow">Ubicación</span>
                        <h2 class="titulo-seccion">Cómo llegar</h2>
                        <p class="mt-4">{{ $hotel->Direccion }}</p>
                        <p class="mb-1">Tel. {{ $hotel->Telefono }}</p>
                        <p class="text-break">{{ $hotel->Correo }}</p>
                    </div>
                    <div class="col-12 col-lg-7">
                        <div id="mapa" class="mapa" data-lat="{{ $hotel->Latitud }}"
                            data-lng="{{ $hotel->Longitud }}"></div>
                    </div>
                </div>
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

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ancla = document.getElementById('reserva-ancla');
            if (!ancla) return;
            const tarjeta = ancla.firstElementChild;

            new IntersectionObserver(([e]) => {
                const fijar = !e.isIntersecting && e.boundingClientRect.top < 0;
                ancla.style.minHeight = fijar ? tarjeta.offsetHeight + 'px' : '';
                tarjeta.classList.toggle('reserva-fija', fijar);
                document.body.classList.toggle('con-reserva-fija', fijar);
            }).observe(ancla);
        });
    </script>
@endPushOnce

@extends('layouts.web')
@section('titulo', 'Inicio')

@section('contenido')
<div class="container">
    {{-- Datos del hotel (tabla Hotel) --}}
    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <h1 class="display-5">{{ $hotel->Nombre }}</h1>
        <p class="lead">{{ $hotel->Descripcion }}</p>
        <p class="text-muted mb-0">{{ $hotel->Direccion }} · {{ $hotel->Telefono }} · {{ $hotel->Correo }}</p>
    </div>

    {{-- Carrusel (tabla ImagenSitio, Tipo = SLIDE): por ahora solo los títulos --}}
    <h2 class="h4">Slides</h2>
    <ul>
        @foreach ($slides as $slide)
            <li>{{ $slide->Titulo }} <small class="text-muted">{{ $slide->ImagenUrl }}</small></li>
        @endforeach
    </ul>

    {{-- Tipos de habitación (tabla TipoHabitacion) --}}
    <h2 class="h4 mt-4">Habitaciones</h2>
    <div class="row g-3">
        @foreach ($tipos as $tipo)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h3 class="h5">{{ $tipo->Nombre }}</h3>
                        <p>{{ $tipo->Descripcion }}</p>
                        <p class="mb-0">Hasta {{ $tipo->Capacidad }} personas ·
                            <strong>₡{{ number_format($tipo->PrecioNoche, 0, ',', '.') }}</strong> por noche</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Reseñas (MongoDB, colección resenas) --}}
    <h2 class="h4 mt-4">Lo que dicen nuestros huéspedes</h2>
    <div class="row g-3">
        @foreach ($resenas as $resena)
            <div class="col-md-4">
                <blockquote class="border-start border-3 ps-3">
                    <p>"{{ $resena->comentario }}"</p>
                    <footer class="text-muted">{{ $resena->huesped }} · {{ $resena->tipoHabitacion }} · {{ str_repeat('★', $resena->calificacion) }}</footer>
                </blockquote>
            </div>
        @endforeach
    </div>
</div>
@endsection

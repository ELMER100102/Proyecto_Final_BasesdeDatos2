@extends('layouts.web')
@section('titulo', $tipo->Nombre)

@section('contenido')
<section class="seccion">
    <div class="container">
        <a class="d-inline-block mb-4" href="{{ route('habitaciones') }}">← Todas las habitaciones</a>

        <div class="row g-4 g-lg-5">
            <div class="col-12 col-lg-7">
                @if ($tipo->FotoUrl)
                    <div class="marco detalle-imagen">
                        <img src="{{ asset($tipo->FotoUrl) }}" alt="Habitación {{ $tipo->Nombre }}">
                    </div>
                @endif
            </div>

            <div class="col-12 col-lg-5">
                <span class="eyebrow">Habitación</span>
                <h1 class="titulo-pagina">{{ $tipo->Nombre }}</h1>
                <p>{{ $tipo->Descripcion }}</p>
                <ul class="datos">
                    <li>Hasta {{ $tipo->Capacidad }} personas</li>
                </ul>
                <p class="precio mb-4">
                    <span class="precio__monto">₡{{ number_format($tipo->PrecioNoche, 0, ',', '.') }}</span>
                    <span class="precio__unidad">por noche + impuesto</span>
                </p>

                {{-- Buscador con el tipo ya elegido --}}
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('reservar') }}" class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="entrada">Llegada</label>
                                <input class="form-control" type="date" id="entrada" name="entrada" min="{{ now()->toDateString() }}" required>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label" for="salida">Salida</label>
                                <input class="form-control" type="date" id="salida" name="salida" min="{{ now()->addDay()->toDateString() }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="personas">Personas</label>
                                <input class="form-control" type="number" id="personas" name="personas" value="1" min="1" max="{{ $tipo->Capacidad }}" required>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary w-100">Ver disponibilidad</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="seccion pt-0">
    <div class="container">
        <header class="seccion-encabezado">
            <span class="eyebrow">Reseñas</span>
            <h2 class="titulo-seccion mb-0">Reseñas de esta habitación</h2>
        </header>
        @if ($resenas->isEmpty())
            <p class="vacio">Todavía no hay reseñas para esta habitación.</p>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($resenas as $resena)
                    <div class="col">
                        @include('web.parts.resena', ['resena' => $resena])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection

@extends('layouts.web')
@section('titulo', 'Reservar')

@section('contenido')
<section class="seccion">
    <div class="container">
        <span class="eyebrow">Reservas</span>
        <h1 class="titulo-pagina">Reservar</h1>

        {{-- Paso 1: fechas y personas --}}
        <div class="card mb-5">
            <div class="card-body">
                <form method="GET" action="{{ route('reservar') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md">
                        <label class="form-label" for="entrada">Llegada</label>
                        <input class="form-control" type="date" id="entrada" name="entrada" value="{{ request('entrada') }}" min="{{ now()->toDateString() }}" required>
                    </div>
                    <div class="col-12 col-md">
                        <label class="form-label" for="salida">Salida</label>
                        <input class="form-control" type="date" id="salida" name="salida" value="{{ request('salida') }}" min="{{ now()->addDay()->toDateString() }}" required>
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label" for="personas">Personas</label>
                        <input class="form-control" type="number" id="personas" name="personas" value="{{ request('personas', 2) }}" min="1" max="10" required>
                    </div>
                    <div class="col-12 col-md-auto">
                        <button class="btn btn-primary w-100">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Paso 2: tipos disponibles --}}
        @if (! is_null($resultados))
            @php
                $noches = \Carbon\Carbon::parse(request('entrada'))->diffInDays(\Carbon\Carbon::parse(request('salida')));
            @endphp

            <header class="seccion-encabezado">
                <span class="eyebrow">Disponibilidad</span>
                <h2 class="titulo-seccion mb-0">
                    Para {{ $noches }} {{ $noches == 1 ? 'noche' : 'noches' }}
                </h2>
            </header>

            @forelse ($resultados as $tipo)
                <div class="row g-4 mb-5">
                    <div class="col-12 col-md-6 col-lg-5">
                        @include('web.parts.tarjeta-habitacion', ['tipo' => $tipo, 'libres' => $tipo->Libres])
                    </div>

                    <div class="col-12 col-md-6 col-lg-7">
                        <div class="card h-100">
                            <div class="card-body d-flex flex-column">
                                <p class="etiqueta mb-1">Total aproximado</p>
                                <p class="precio mb-4">
                                    <span class="precio__monto">₡{{ number_format($tipo->PrecioNoche * $noches, 0, ',', '.') }}</span>
                                    <span class="precio__unidad">+ impuesto</span>
                                </p>

                                @auth
                                    @if (auth()->user()->Rol === 'HUESPED')
                                        {{-- Paso 3: confirmar (sp_CrearReserva) --}}
                                        <form method="POST" action="{{ route('reservar.guardar') }}" class="row g-3 mt-auto">
                                            @csrf
                                            <input type="hidden" name="tipo" value="{{ $tipo->TipoHabitacionId }}">
                                            <input type="hidden" name="entrada" value="{{ request('entrada') }}">
                                            <input type="hidden" name="salida" value="{{ request('salida') }}">
                                            <input type="hidden" name="personas" value="{{ request('personas') }}">

                                            <div class="col-12 col-lg-6">
                                                <label class="form-label" for="adelanto-{{ $tipo->TipoHabitacionId }}">Adelanto (opcional)</label>
                                                <input class="form-control" type="number" id="adelanto-{{ $tipo->TipoHabitacionId }}" name="adelanto" min="0" step="1000">
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <label class="form-label" for="metodo-{{ $tipo->TipoHabitacionId }}">Método de pago</label>
                                                <select class="form-select" id="metodo-{{ $tipo->TipoHabitacionId }}" name="metodo">
                                                    <option value="">Sin adelanto</option>
                                                    <option value="SINPE">SINPE Móvil</option>
                                                    <option value="TARJETA">Tarjeta</option>
                                                    <option value="EFECTIVO">Efectivo</option>
                                                </select>
                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-primary w-100">Reservar</button>
                                            </div>
                                        </form>
                                    @else
                                        <p class="nota mt-auto mb-0">Las reservas del personal se hacen desde el panel.</p>
                                    @endif
                                @else
                                    <div class="mt-auto">
                                        <a class="btn btn-primary w-100" href="{{ route('login') }}">Inicie sesión para reservar</a>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="vacio">No hay habitaciones disponibles para esas fechas. Pruebe con otras.</p>
            @endforelse
        @endif
    </div>
</section>
@endsection

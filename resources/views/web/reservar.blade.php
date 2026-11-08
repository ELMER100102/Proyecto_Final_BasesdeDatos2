@extends('layouts.web')
@section('titulo', 'Reservar')

@section('contenido')
<section class="seccion">
    <div class="contenedor">
        <h1 class="pagina__titulo">Reservar</h1>

        {{-- Paso 1: fechas y personas --}}
        <form class="buscador" method="GET" action="{{ route('reservar') }}">
            <div class="campo">
                <label for="entrada">Llegada</label>
                <input type="date" id="entrada" name="entrada" value="{{ request('entrada') }}" min="{{ now()->toDateString() }}" required>
            </div>
            <div class="campo">
                <label for="salida">Salida</label>
                <input type="date" id="salida" name="salida" value="{{ request('salida') }}" min="{{ now()->addDay()->toDateString() }}" required>
            </div>
            <div class="campo">
                <label for="personas">Personas</label>
                <input type="number" id="personas" name="personas" value="{{ request('personas', 2) }}" min="1" max="10" required>
            </div>
            <button class="boton boton--primario">Buscar</button>
        </form>

        {{-- Paso 2: tipos disponibles --}}
        @if (! is_null($resultados))
            @php
                $noches = \Carbon\Carbon::parse(request('entrada'))->diffInDays(\Carbon\Carbon::parse(request('salida')));
            @endphp

            <h2 class="seccion__titulo">
                Disponibilidad para {{ $noches }} {{ $noches == 1 ? 'noche' : 'noches' }}
            </h2>

            @forelse ($resultados as $tipo)
                <div class="resultado">
                    @include('web.parts.tarjeta-habitacion', ['tipo' => $tipo, 'libres' => $tipo->Libres])

                    <div class="resultado__accion">
                        <p class="precio">
                            <span class="precio__unidad">Total aproximado</span>
                            <span class="precio__monto">₡{{ number_format($tipo->PrecioNoche * $noches, 0, ',', '.') }}</span>
                            <span class="precio__unidad">+ impuesto</span>
                        </p>

                        @auth
                            @if (auth()->user()->Rol === 'HUESPED')
                                {{-- Paso 3: confirmar (sp_CrearReserva) --}}
                                <form class="formulario" method="POST" action="{{ route('reservar.guardar') }}">
                                    @csrf
                                    <input type="hidden" name="tipo" value="{{ $tipo->TipoHabitacionId }}">
                                    <input type="hidden" name="entrada" value="{{ request('entrada') }}">
                                    <input type="hidden" name="salida" value="{{ request('salida') }}">
                                    <input type="hidden" name="personas" value="{{ request('personas') }}">

                                    <div class="campo">
                                        <label for="adelanto-{{ $tipo->TipoHabitacionId }}">Adelanto (opcional)</label>
                                        <input type="number" id="adelanto-{{ $tipo->TipoHabitacionId }}" name="adelanto" min="0" step="1000">
                                    </div>
                                    <div class="campo">
                                        <label for="metodo-{{ $tipo->TipoHabitacionId }}">Método de pago</label>
                                        <select id="metodo-{{ $tipo->TipoHabitacionId }}" name="metodo">
                                            <option value="">Sin adelanto</option>
                                            <option value="SINPE">SINPE Móvil</option>
                                            <option value="TARJETA">Tarjeta</option>
                                            <option value="EFECTIVO">Efectivo</option>
                                        </select>
                                    </div>
                                    <button class="boton boton--primario">Reservar</button>
                                </form>
                            @else
                                <p class="nota">Las reservas del personal se hacen desde el panel.</p>
                            @endif
                        @else
                            <p class="nota">
                                <a class="boton boton--primario" href="{{ route('login') }}">Inicie sesión para reservar</a>
                            </p>
                        @endauth
                    </div>
                </div>
            @empty
                <p class="vacio">No hay habitaciones disponibles para esas fechas. Pruebe con otras.</p>
            @endforelse
        @endif
    </div>
</section>
@endsection

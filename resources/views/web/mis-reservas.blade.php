@extends('layouts.web')
@section('titulo', 'Mis reservas')

@section('contenido')
<section class="seccion">
    <div class="contenedor">
        <h1 class="pagina__titulo">Mis reservas</h1>

        @if ($reservas->isEmpty())
            <p class="vacio">Todavía no tiene reservas. <a class="enlace" href="{{ route('reservar') }}">Haga la primera</a>.</p>
        @else
            <div class="tabla-contenedor">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Habitación</th>
                            <th>Llegada</th>
                            <th>Salida</th>
                            <th>Estado</th>
                            <th class="tabla__numero">Total</th>
                            <th class="tabla__numero">Pagado</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservas as $r)
                            @php $detalle = $r->detalles->first(); @endphp
                            <tr>
                                <td>{{ $r->ReservaId }}</td>
                                <td>{{ $detalle?->habitacion?->tipo?->Nombre }} {{ $detalle?->habitacion?->Numero }}</td>
                                <td>{{ $r->FechaEntrada->format('d/m/Y') }}</td>
                                <td>{{ $r->FechaSalida->format('d/m/Y') }}</td>
                                <td><span class="estado estado--{{ strtolower($r->Estado) }}">{{ str_replace('_', ' ', $r->Estado) }}</span></td>
                                <td class="tabla__numero">₡{{ number_format($r->Total, 0, ',', '.') }}</td>
                                <td class="tabla__numero">₡{{ number_format($r->pagado, 0, ',', '.') }}</td>
                                <td class="tabla__acciones">
                                    @if (in_array($r->Estado, ['PENDIENTE', 'CONFIRMADA']))
                                        <form method="POST" action="{{ route('mis-reservas.cancelar', $r->ReservaId) }}"
                                              onsubmit="return confirm('¿Cancelar esta reserva? Si faltan menos de 3 días se retiene el 50 % de lo pagado.')">
                                            @csrf
                                            <button class="boton boton--peligro">Cancelar</button>
                                        </form>
                                    @elseif ($r->Estado === 'FINALIZADA' && ! in_array($r->ReservaId, $conResena))
                                        <a class="boton boton--secundario" href="{{ route('resena.crear', $r->ReservaId) }}">Dejar reseña</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection

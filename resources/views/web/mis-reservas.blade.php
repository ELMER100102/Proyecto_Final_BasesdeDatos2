@extends('layouts.web')
@section('titulo', 'Mis reservas')

@section('contenido')
<section class="seccion">
    <div class="container">
        <span class="eyebrow">Mi cuenta</span>
        <h1 class="titulo-pagina">Mis reservas</h1>

        @if ($reservas->isEmpty())
            <p class="vacio">Todavía no tiene reservas. <a href="{{ route('reservar') }}">Haga la primera</a>.</p>
        @else
            <div class="card">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Habitación</th>
                                <th scope="col">Llegada</th>
                                <th scope="col">Salida</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-end">Total</th>
                                <th scope="col" class="text-end">Pagado</th>
                                <th scope="col"><span class="visually-hidden">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservas as $r)
                                @php $detalle = $r->detalles->first(); @endphp
                                <tr>
                                    <td>{{ $r->ReservaId }}</td>
                                    <td class="text-nowrap">{{ $detalle?->habitacion?->tipo?->Nombre }} {{ $detalle?->habitacion?->Numero }}</td>
                                    <td class="text-nowrap">{{ $r->FechaEntrada->format('d/m/Y') }}</td>
                                    <td class="text-nowrap">{{ $r->FechaSalida->format('d/m/Y') }}</td>
                                    <td><span class="badge rounded-pill estado estado-{{ strtolower($r->Estado) }}">{{ str_replace('_', ' ', $r->Estado) }}</span></td>
                                    <td class="text-end text-nowrap">₡{{ number_format($r->Total, 0, ',', '.') }}</td>
                                    <td class="text-end text-nowrap">₡{{ number_format($r->pagado, 0, ',', '.') }}</td>
                                    <td class="text-end">
                                        @if (in_array($r->Estado, ['PENDIENTE', 'CONFIRMADA']))
                                            <form method="POST" action="{{ route('mis-reservas.cancelar', $r->ReservaId) }}" class="m-0"
                                                  onsubmit="return confirm('¿Cancelar esta reserva? Si faltan menos de 3 días se retiene el 50 % de lo pagado.')">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger">Cancelar</button>
                                            </form>
                                        @elseif ($r->Estado === 'FINALIZADA' && ! in_array($r->ReservaId, $conResena))
                                            <a class="btn btn-sm btn-outline-dark text-nowrap" href="{{ route('resena.crear', $r->ReservaId) }}">Dejar reseña</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

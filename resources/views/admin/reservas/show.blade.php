@extends('layouts.admin')
@section('titulo', 'Reserva #' . $reserva->ReservaId)

@section('contenido')
    @php $persona = $reserva->huesped->persona; @endphp

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h2 class="h4 mb-1">{{ $persona->nombre_completo }} @include('admin.parts.estado', ['estado' => $reserva->Estado])</h2>
            <div class="text-muted">
                {{ $reserva->FechaEntrada->format('d/m/Y') }} al {{ $reserva->FechaSalida->format('d/m/Y') }}
                · {{ $reserva->CantPersonas }} personas · {{ $persona->Correo }}
            </div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.huespedes.show', $reserva->HuespedId) }}" class="btn btn-outline-secondary">Ver
                huésped</a>
            <a href="{{ route('admin.reservas.index') }}" class="btn btn-link">Volver</a>
        </div>
    </div>

    <div class="row g-3">
        {{-- Columna izquierda: detalle --}}
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">Habitaciones</div>
                <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Habitación</th>
                            <th>Tipo</th>
                            <th>Noches</th>
                            <th class="text-end">Precio noche</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reserva->detalles as $d)
                            <tr>
                                <td>{{ $d->habitacion->Numero }} @include('admin.parts.estado', ['estado' => $d->habitacion->Estado])</td>
                                <td>{{ $d->habitacion->tipo->Nombre }}</td>
                                <td>{{ $d->Noches }}</td>
                                <td class="text-end">₡{{ number_format($d->PrecioNoche, 0, ',', '.') }}</td>
                                <td class="text-end">₡{{ number_format($d->Subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">Consumos</div>
                <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Servicio</th>
                            <th>Cantidad</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reserva->cargos as $c)
                            <tr>
                                <td>{{ $c->Fecha->format('d/m/Y H:i') }}</td>
                                <td>{{ $c->servicio->Nombre }}</td>
                                <td>{{ $c->Cantidad }}</td>
                                <td class="text-end">₡{{ number_format($c->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">Sin consumos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Pagos</div>
                <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Método</th>
                            <th class="text-end">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reserva->pagos as $p)
                            <tr>
                                <td>{{ $p->Fecha->format('d/m/Y H:i') }}</td>
                                <td>{{ $p->Tipo }}</td>
                                <td>{{ $p->Metodo }}</td>
                                <td class="text-end {{ $p->Tipo === 'REEMBOLSO' ? 'text-danger' : '' }}">
                                    {{ $p->Tipo === 'REEMBOLSO' ? '-' : '' }}₡{{ number_format($p->Monto, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-muted">Sin pagos.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        {{-- Columna derecha: totales y acciones según el estado --}}
        <div class="col-lg-4">
            <div class="card mb-3 resumen-totales">
                <div class="card-body">
                    <div class="fila-total"><span>Total (con
                            impuesto)</span><strong class="monto">₡{{ number_format($reserva->Total, 0, ',', '.') }}</strong></div>
                    <div class="fila-total">
                        <span>Pagado</span><span class="monto">₡{{ number_format($reserva->pagado, 0, ',', '.') }}</span></div>
                    <hr>
                    <div class="fila-total fila-total--saldo">
                        <span>Saldo</span><strong class="monto">₡{{ number_format($saldo, 0, ',', '.') }}</strong></div>
                </div>
            </div>

            @if (in_array($reserva->Estado, ['PENDIENTE', 'CONFIRMADA']))
                <div class="card mb-3">
                    <div class="card-body d-grid gap-2">
                        <form method="POST" action="{{ route('admin.reservas.checkin', $reserva->ReservaId) }}"
                            class="d-grid">
                            @csrf
                            <button class="btn btn-primary">Hacer check-in</button>
                        </form>
                        <form method="POST" action="{{ route('admin.reservas.cancelar', $reserva->ReservaId) }}"
                            class="d-grid"
                            onsubmit="return confirm('¿Cancelar la reserva? Con menos de 3 días de anticipación se retiene el 50 % de lo pagado.')">
                            @csrf
                            <button class="btn btn-outline-danger">Cancelar reserva</button>
                        </form>
                    </div>
                </div>
            @endif

            @if ($reserva->Estado === 'EN_CURSO')
                <div class="card mb-3">
                    <div class="card-header">Agregar consumo</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.reservas.cargo', $reserva->ReservaId) }}"
                            class="row g-2">
                            @csrf
                            <div class="col-8">
                                <select name="ServicioId" class="form-select" aria-label="Servicio" required>
                                    @foreach ($servicios as $s)
                                        <option value="{{ $s->ServicioId }}">{{ $s->Nombre }} ·
                                            ₡{{ number_format($s->Precio, 0, ',', '.') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <input type="number" name="Cantidad" value="1" min="1" class="form-control"
                                    aria-label="Cantidad" required>
                            </div>
                            <div class="col-12 d-grid"><button class="btn btn-outline-dark">Agregar</button></div>
                        </form>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header">Check-out</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.reservas.checkout', $reserva->ReservaId) }}"
                            class="row g-2">
                            @csrf
                            <div class="col-7">
                                <label class="form-label" for="Monto">Monto a pagar</label>
                                <input type="number" id="Monto" name="Monto" value="{{ $saldo }}"
                                    min="0" step="0.01" class="form-control">
                            </div>
                            <div class="col-5">
                                <label class="form-label" for="Metodo">Método</label>
                                <select id="Metodo" name="Metodo" class="form-select">
                                    @foreach (['TARJETA', 'SINPE', 'EFECTIVO'] as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 d-grid"><button class="btn btn-primary">Cobrar y hacer check-out</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

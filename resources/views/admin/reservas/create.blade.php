@extends('layouts.admin')
@section('titulo', 'Nueva reserva')

@section('contenido')
<div class="card" style="max-width: 760px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.reservas.store') }}" class="row g-3">
            @csrf
            <div class="col-md-8">
                <label for="HuespedId" class="form-label">Huésped</label>
                <select id="HuespedId" name="HuespedId" class="form-select" required>
                    <option value="">Seleccione…</option>
                    @foreach ($huespedes as $h)
                        <option value="{{ $h->PersonaId }}" @selected(old('HuespedId') == $h->PersonaId)>
                            {{ $h->persona->nombre_completo }} · {{ $h->persona->Correo }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <a href="{{ route('admin.huespedes.create') }}" class="btn btn-outline-secondary w-100">Registrar huésped nuevo</a>
            </div>

            <div class="col-md-8">
                <label for="TipoHabitacionId" class="form-label">Tipo de habitación</label>
                <select id="TipoHabitacionId" name="TipoHabitacionId" class="form-select" required>
                    @foreach ($tipos as $t)
                        <option value="{{ $t->TipoHabitacionId }}" @selected(old('TipoHabitacionId') == $t->TipoHabitacionId)>
                            {{ $t->Nombre }} · hasta {{ $t->Capacidad }} personas · ₡{{ number_format($t->PrecioNoche, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="CantPersonas" class="form-label">Personas</label>
                <input type="number" id="CantPersonas" name="CantPersonas" value="{{ old('CantPersonas', 2) }}" min="1" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="FechaEntrada" class="form-label">Entrada</label>
                <input type="date" id="FechaEntrada" name="FechaEntrada" value="{{ old('FechaEntrada', now()->toDateString()) }}" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="FechaSalida" class="form-label">Salida</label>
                <input type="date" id="FechaSalida" name="FechaSalida" value="{{ old('FechaSalida', now()->addDay()->toDateString()) }}" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="Adelanto" class="form-label">Adelanto (opcional)</label>
                <input type="number" id="Adelanto" name="Adelanto" value="{{ old('Adelanto') }}" min="0" step="1000" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="Metodo" class="form-label">Método del adelanto</label>
                <select id="Metodo" name="Metodo" class="form-select">
                    <option value="">Sin adelanto</option>
                    @foreach (['SINPE', 'TARJETA', 'EFECTIVO'] as $m)
                        <option value="{{ $m }}" @selected(old('Metodo') === $m)>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-12">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="EsperaDemo" name="EsperaDemo" value="1">
                    <label class="form-check-label" for="EsperaDemo">
                        Modo demostración de concurrencia: retener el bloqueo 10 segundos
                    </label>
                </div>
                <div class="form-text">Para la defensa: abra este formulario en dos navegadores, marque esta casilla en el primero y envíe los dos casi a la vez.</div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary">Crear reserva</button>
                <a href="{{ route('admin.reservas.index') }}" class="btn btn-link">Volver</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin')
@section('titulo', $habitacion->exists ? 'Editar habitación ' . $habitacion->Numero : 'Nueva habitación')

@section('contenido')
<div class="card" style="max-width: 560px;">
    <div class="card-body">
        <form method="POST" action="{{ $habitacion->exists ? route('admin.habitaciones.update', $habitacion) : route('admin.habitaciones.store') }}" class="row g-3">
            @csrf
            @if ($habitacion->exists) @method('PUT') @endif
            <div class="col-md-6"><label class="form-label" for="Numero">Número</label><input id="Numero" name="Numero" value="{{ old('Numero', $habitacion->Numero) }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label" for="Piso">Piso</label><input type="number" id="Piso" name="Piso" value="{{ old('Piso', $habitacion->Piso) }}" min="0" class="form-control" required></div>
            <div class="col-12">
                <label class="form-label" for="TipoHabitacionId">Tipo</label>
                <select id="TipoHabitacionId" name="TipoHabitacionId" class="form-select" required>
                    @foreach ($tipos as $t)
                        <option value="{{ $t->TipoHabitacionId }}" @selected(old('TipoHabitacionId', $habitacion->TipoHabitacionId) == $t->TipoHabitacionId)>{{ $t->Nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label" for="Estado">Estado</label>
                <select id="Estado" name="Estado" class="form-select" required>
                    @foreach ($estados as $e)
                        <option value="{{ $e }}" @selected(old('Estado', $habitacion->Estado) === $e)>{{ $e }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('admin.habitaciones.index') }}" class="btn btn-link">Volver</a>
            </div>
        </form>
    </div>
</div>
@endsection

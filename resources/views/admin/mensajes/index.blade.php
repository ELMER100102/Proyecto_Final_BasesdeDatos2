@extends('layouts.admin')
@section('titulo', 'Mensajes de contacto (MongoDB)')

@section('contenido')
@forelse ($mensajes as $m)
    <div class="card mb-3 {{ $m->respondido ? 'mensaje-respondido' : 'mensaje-pendiente' }}">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-wrap gap-2">
                <div>
                    <strong>{{ $m->asunto }}</strong>
                    <span class="badge estado estado-mensaje-{{ $m->respondido ? 'respondido' : 'pendiente' }}">{{ $m->respondido ? 'Respondido' : 'Pendiente' }}</span>
                    <div class="small text-muted">{{ $m->nombre }} · {{ $m->correo }} {{ $m->telefono ? '· ' . $m->telefono : '' }}</div>
                </div>
                <div class="text-muted small">{{ $m->fecha?->format('d/m/Y H:i') }}</div>
            </div>
            <p class="my-2">{{ $m->mensaje }}</p>
        </div>
        <div class="card-footer d-flex justify-content-end gap-2">
            <a href="mailto:{{ $m->correo }}?subject=Re: {{ rawurlencode($m->asunto) }}" class="btn btn-sm btn-outline-secondary">Responder por correo</a>
            <form method="POST" action="{{ route('admin.mensajes.marcar', $m->id) }}">
                @csrf
                <button class="btn btn-sm btn-outline-dark">{{ $m->respondido ? 'Marcar pendiente' : 'Marcar respondido' }}</button>
            </form>
            @include('admin.parts.eliminar', ['ruta' => route('admin.mensajes.destroy', $m->id)])
        </div>
    </div>
@empty
    <p class="text-muted">No hay mensajes.</p>
@endforelse
@endsection

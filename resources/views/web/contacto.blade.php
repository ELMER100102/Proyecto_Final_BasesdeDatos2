@extends('layouts.web')
@section('titulo', 'Contacto')

@section('contenido')
<section class="seccion">
    <div class="container">
        <div class="row g-4 g-lg-5">
            <div class="col-12 col-lg-5">
                <span class="eyebrow">Contacto</span>
                <h1 class="titulo-pagina">Contáctenos</h1>
                <p>{{ $hotelSitio->Direccion ?? '' }}</p>
                <p class="mb-1">Tel. {{ $hotelSitio->Telefono ?? '' }}</p>
                <p class="text-break">{{ $hotelSitio->Correo ?? '' }}</p>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('contacto.enviar') }}" class="row g-3">
                            @csrf
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="nombre">Nombre</label>
                                <input class="form-control" id="nombre" name="nombre" value="{{ old('nombre', auth()->user()?->persona?->nombre_completo) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="correo">Correo</label>
                                <input class="form-control" type="email" id="correo" name="correo" value="{{ old('correo', auth()->user()?->persona?->Correo) }}" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="telefono">Teléfono (opcional)</label>
                                <input class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="asunto">Asunto</label>
                                <input class="form-control" id="asunto" name="asunto" value="{{ old('asunto') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label" for="mensaje">Mensaje</label>
                                <textarea class="form-control" id="mensaje" name="mensaje" rows="5" required>{{ old('mensaje') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary">Enviar mensaje</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

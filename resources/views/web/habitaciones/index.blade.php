@extends('layouts.web')
@section('titulo', 'Habitaciones')

@section('contenido')
<section class="seccion">
    <div class="container">
        <span class="eyebrow">Hospedaje</span>
        <h1 class="titulo-pagina">Habitaciones</h1>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($tipos as $tipo)
                <div class="col">
                    @include('web.parts.tarjeta-habitacion', [
                        'tipo' => $tipo,
                        'accion' => new \Illuminate\Support\HtmlString(
                            '<a class="btn btn-outline-dark w-100" href="' . route('habitaciones.show', $tipo->TipoHabitacionId) . '">Ver detalle</a>'
                        ),
                    ])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

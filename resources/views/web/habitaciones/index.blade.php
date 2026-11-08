@extends('layouts.web')
@section('titulo', 'Habitaciones')

@section('contenido')
<section class="seccion">
    <div class="contenedor">
        <h1 class="pagina__titulo">Habitaciones</h1>
        <div class="rejilla">
            @foreach ($tipos as $tipo)
                @include('web.parts.tarjeta-habitacion', [
                    'tipo' => $tipo,
                    'accion' => new \Illuminate\Support\HtmlString(
                        '<a class="boton boton--secundario" href="' . route('habitaciones.show', $tipo->TipoHabitacionId) . '">Ver detalle</a>'
                    ),
                ])
            @endforeach
        </div>
    </div>
</section>
@endsection

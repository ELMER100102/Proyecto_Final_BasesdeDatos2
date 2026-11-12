@extends('layouts.admin')
@section('titulo', 'Imágenes del sitio')

@section('acciones')
    <a href="{{ route('admin.imagenes.create') }}" class="btn btn-primary">Agregar imagen</a>
@endsection

@section('contenido')
<div class="row g-3">
    @foreach ($imagenes as $img)
        <div class="col-sm-6 col-lg-3">
            <div class="card h-100">
                <img src="{{ asset($img->ImagenUrl) }}" alt="{{ $img->Titulo }}" class="miniatura" style="aspect-ratio:4/3;object-fit:cover">
                <div class="card-body">
                    <div class="small text-muted">{{ $img->Tipo === 'SLIDE' ? 'Carrusel' : 'Galería' }} · orden {{ $img->Orden }} {{ $img->Activo ? '' : '· oculta' }}</div>
                    <div class="fw-semibold">{{ $img->Titulo ?? 'Sin título' }}</div>
                </div>
                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('admin.imagenes.edit', $img) }}" class="btn btn-sm btn-outline-dark">Editar</a>
                    @include('admin.parts.eliminar', ['ruta' => route('admin.imagenes.destroy', $img)])
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

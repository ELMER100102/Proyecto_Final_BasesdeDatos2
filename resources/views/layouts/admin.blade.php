<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Panel') | Administración</title>

    @include('admin.parts.css')

    @stack('estilos')
</head>
<body>

@php $u = auth()->user(); @endphp

{{-- Barra superior (solo en celular): abre el menú lateral --}}
@include('admin.parts.barra-superior')

<div class="admin-shell d-flex">
    {{-- Menú lateral (offcanvas en celular) --}}
    @include('admin.parts.sidebar')

    <div class="admin-main">
        <main class="admin-contenido">
            <div class="encabezado-pagina">
                <h1 class="titulo-pagina">@yield('titulo', 'Panel')</h1>
                @hasSection('acciones')
                    <div class="d-flex flex-wrap gap-2 no-imprimir">@yield('acciones')</div>
                @endif
            </div>

            @if (session('ok'))
                <div class="alert alert-success">{{ session('ok') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('contenido')
        </main>
    </div>
</div>

@include('admin.parts.js')

@stack('scripts')
</body>
</html>

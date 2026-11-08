<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Inicio') | Hotel Arrecife Azul</title>

    {{-- PROVISIONAL: Bootstrap por CDN. Al integrar Grandoria, reemplazar por sus archivos:
         <link href="{{ asset('web/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
         <link href="{{ asset('web/assets/css/main.css') }}" rel="stylesheet"> --}}
    
    @include('web.parts.css')

    @stack('estilos')
</head>
<body class="d-flex flex-column min-vh-100">

@include('web.parts.header')

<main class="flex-grow-1">
    <div class="container mt-3">
        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
    </div>

    @yield('contenido')
</main>

@include('web.parts.footer')

@include('web.parts.js')

@stack('scripts')
</body>
</html>

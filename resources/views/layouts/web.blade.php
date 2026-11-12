<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Inicio') | Hotel Arrecife Azul</title>
    
    @include('web.parts.css')

    @stack('estilos')
</head>
<body class="d-flex flex-column min-vh-100">

@include('web.parts.header')

<main class="flex-grow-1">
    @include('web.parts.alertas')
    @yield('contenido')
</main>

@include('web.parts.footer')

@include('web.parts.js')

@stack('scripts')
</body>
</html>

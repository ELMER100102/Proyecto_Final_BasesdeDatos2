<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('titulo', 'Panel') | Administración</title>

    {{-- PROVISIONAL: Bootstrap por CDN. Al integrar AdminLTE, reemplazar por sus archivos:
         <link href="{{ asset('admin/css/adminlte.min.css') }}" rel="stylesheet"> --}}
    @include('admin.parts.css')

    @stack('estilos')
</head>
<body class="bg-light">

<div class="d-flex min-vh-100">
    {{-- Menú lateral --}}
    @include('admin.parts.sidebar')

    <div class="flex-grow-1">
        {{-- Barra superior --}}
        @include('admin.parts.barra-superior')
        
        <main class="p-4">
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

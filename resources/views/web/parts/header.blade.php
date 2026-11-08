<header class="encabezado">
    <div class="contenedor encabezado__contenido">
        <a class="logo" href="{{ route('inicio') }}">
            @if ($hotelSitio?->LogoUrl)
                <img class="logo__imagen" src="{{ asset($hotelSitio->LogoUrl) }}" alt="">
            @endif
            <span class="logo__texto">{{ $hotelSitio->Nombre ?? 'Hotel' }}</span>
        </a>

        <nav class="menu" aria-label="Menú principal">
            <a class="menu__enlace {{ request()->routeIs('inicio') ? 'menu__enlace--activo' : '' }}" href="{{ route('inicio') }}">Inicio</a>
            <a class="menu__enlace {{ request()->routeIs('habitaciones*') ? 'menu__enlace--activo' : '' }}" href="{{ route('habitaciones') }}">Habitaciones</a>
            <a class="menu__enlace {{ request()->routeIs('reservar') ? 'menu__enlace--activo' : '' }}" href="{{ route('reservar') }}">Reservar</a>
            <a class="menu__enlace {{ request()->routeIs('contacto') ? 'menu__enlace--activo' : '' }}" href="{{ route('contacto') }}">Contacto</a>
        </nav>

        <div class="sesion">
            @auth
                @if (auth()->user()->esEmpleado())
                    <a class="menu__enlace" href="{{ route('admin.dashboard') }}">Panel</a>
                @else
                    <a class="menu__enlace" href="{{ route('mis-reservas') }}">Mis reservas</a>
                @endif
                <span class="sesion__nombre">{{ auth()->user()->persona->Nombre }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="boton boton--texto">Salir</button>
                </form>
            @else
                <a class="menu__enlace" href="{{ route('login') }}">Iniciar sesión</a>
                <a class="boton boton--primario" href="{{ route('registro') }}">Registrarse</a>
            @endauth
        </div>
    </div>
</header>
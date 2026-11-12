<header>
    <nav class="navbar navbar-expand-lg barra-sitio" data-bs-theme="dark" aria-label="Menú principal">
        <div class="container">
            <a class="navbar-brand" href="{{ route('inicio') }}">
                @if ($hotelSitio?->LogoUrl)
                    <img src="{{ asset($hotelSitio->LogoUrl) }}" alt="">
                @endif
                <span>{{ $hotelSitio->Nombre ?? 'Hotel' }}</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuSitio"
                    aria-controls="menuSitio" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuSitio">
                <ul class="navbar-nav mx-lg-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('inicio') ? 'active' : '' }}" @if (request()->routeIs('inicio')) aria-current="page" @endif href="{{ route('inicio') }}">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('habitaciones*') ? 'active' : '' }}" @if (request()->routeIs('habitaciones*')) aria-current="page" @endif href="{{ route('habitaciones') }}">Habitaciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('reservar') ? 'active' : '' }}" @if (request()->routeIs('reservar')) aria-current="page" @endif href="{{ route('reservar') }}">Reservar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contacto') ? 'active' : '' }}" @if (request()->routeIs('contacto')) aria-current="page" @endif href="{{ route('contacto') }}">Contacto</a>
                    </li>
                </ul>

                <div class="sesion d-flex flex-wrap align-items-center gap-2 gap-lg-3">
                    @auth
                        @if (auth()->user()->esEmpleado())
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Panel</a>
                        @else
                            <a class="nav-link {{ request()->routeIs('mis-reservas', 'resena.*') ? 'active' : '' }}" href="{{ route('mis-reservas') }}">Mis reservas</a>
                        @endif
                        <span class="usuario">{{ auth()->user()->persona->Nombre }}</span>
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button class="btn-salir">Salir</button>
                        </form>
                    @else
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Iniciar sesión</a>
                        <a class="btn btn-contorno-claro" href="{{ route('registro') }}">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('inicio') }}">Hotel Arrecife Azul</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('inicio') }}">Inicio</a></li>
                {{-- Agregar aquí: Habitaciones, Reservar, Contacto --}}
            </ul>
            <ul class="navbar-nav">
                @auth
                    @if (auth()->user()->esEmpleado())
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Panel</a></li>
                    @endif
                    <li class="nav-item"><span class="nav-link">{{ auth()->user()->persona->Nombre }}</span></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-link nav-link">Salir</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar sesión</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('registro') }}">Registrarse</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
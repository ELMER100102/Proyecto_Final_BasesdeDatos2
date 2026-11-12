{{-- Menú lateral: fijo desde 992px; en celular es un offcanvas que abre la barra superior --}}
<aside class="offcanvas-lg offcanvas-start menu-admin" tabindex="-1" id="menuAdmin" aria-label="Menú del panel">
    <div class="offcanvas-header d-lg-none">
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
            data-bs-target="#menuAdmin" aria-label="Cerrar menú"></button>
    </div>

    <div class="offcanvas-body">
        <a href="{{ route('admin.dashboard') }}" class="menu-admin__marca">
            <span class="menu-admin__nombre">{{ $hotelSitio->Nombre ?? 'Hotel' }}</span>
            <span class="menu-admin__sub">Panel administrativo</span>
        </a>

        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">Inicio</a>

            <div class="titulo">Operación</div>
            <a class="nav-link {{ request()->routeIs('admin.reservas.*') ? 'active' : '' }}"
                href="{{ route('admin.reservas.index') }}">Reservas</a>
            <a class="nav-link {{ request()->routeIs('admin.huespedes.*') ? 'active' : '' }}"
                href="{{ route('admin.huespedes.index') }}">Huéspedes</a>
            <a class="nav-link {{ request()->routeIs('admin.habitaciones.estado.index') ? 'active' : '' }}"
                href="{{ route('admin.habitaciones.estado.index') }}">Estado de habitaciones</a>
            <a class="nav-link {{ request()->routeIs('admin.reporte') ? 'active' : '' }}"
                href="{{ route('admin.reporte') }}">Reporte de ingresos</a>

            @if ($u->esAdmin())
                <div class="titulo">Configuración</div>
                <a class="nav-link {{ request()->routeIs('admin.tipos.*') ? 'active' : '' }}"
                    href="{{ route('admin.tipos.index') }}">Tipos de habitación</a>
                <a class="nav-link {{ request()->routeIs('admin.habitaciones.index', 'admin.habitaciones.create', 'admin.habitaciones.edit') ? 'active' : '' }}"
                    href="{{ route('admin.habitaciones.index') }}">Habitaciones</a>
                <a class="nav-link {{ request()->routeIs('admin.servicios.*') ? 'active' : '' }}"
                    href="{{ route('admin.servicios.index') }}">Servicios</a>
                <a class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}"
                    href="{{ route('admin.usuarios.index') }}">Usuarios</a>

                <div class="titulo">Sitio web</div>
                <a class="nav-link {{ request()->routeIs('admin.hotel.*') ? 'active' : '' }}"
                    href="{{ route('admin.hotel.edit') }}">Datos del hotel</a>
                <a class="nav-link {{ request()->routeIs('admin.imagenes.*') ? 'active' : '' }}"
                    href="{{ route('admin.imagenes.index') }}">Imágenes</a>
                <a class="nav-link {{ request()->routeIs('admin.resenas.*') ? 'active' : '' }}"
                    href="{{ route('admin.resenas.index') }}">Reseñas</a>
                <a class="nav-link {{ request()->routeIs('admin.mensajes.*') ? 'active' : '' }}"
                    href="{{ route('admin.mensajes.index') }}">Mensajes</a>
            @endif

            <a class="nav-link nav-link--externo" href="{{ route('inicio') }}">Ver sitio web ↗</a>
        </nav>

        <div class="menu-admin__usuario">
            <div class="menu-admin__usuario-datos">
                <span class="menu-admin__usuario-nombre">{{ $u->persona->Nombre }}</span>
                <span class="menu-admin__usuario-rol">{{ $u->Rol }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-contorno-claro">Salir</button>
            </form>
        </div>
    </div>
</aside>

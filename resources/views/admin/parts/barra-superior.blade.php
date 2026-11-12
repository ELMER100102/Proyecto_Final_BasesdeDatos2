{{-- Barra verde visible solo por debajo de 992px; el menú lateral se abre como offcanvas --}}
<header class="barra-movil d-lg-none">
    <a href="{{ route('admin.dashboard') }}" class="barra-movil__marca">Arrecife Azul · Panel</a>
    <button class="btn btn-sm btn-contorno-claro" type="button" data-bs-toggle="offcanvas"
        data-bs-target="#menuAdmin" aria-controls="menuAdmin" aria-label="Abrir menú">
        <span class="icono-menu" aria-hidden="true"></span>Menú
    </button>
</header>

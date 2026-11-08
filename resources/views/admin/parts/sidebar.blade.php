<aside class="bg-dark text-white p-3" style="width: 230px;">
        <h5 class="mb-4">Arrecife Azul</h5>
        <ul class="nav flex-column">
            <li class="nav-item"><a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Inicio</a></li>
            {{-- Operación (administrador y recepcionista): Reservas, Reporte --}}
            @if (auth()->user()->esAdmin())
                {{-- Configuración (solo administrador): Tipos, Habitaciones, Servicios, Hotel, Imágenes, Usuarios, Reseñas, Mensajes --}}
            @endif
            <li class="nav-item mt-3"><a class="nav-link text-white-50" href="{{ route('inicio') }}">Ver sitio web</a></li>
        </ul>
</aside>
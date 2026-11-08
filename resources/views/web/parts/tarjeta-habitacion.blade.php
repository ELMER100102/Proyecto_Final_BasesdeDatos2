{{-- Tarjeta de un tipo de habitación. Recibe $tipo y, opcionalmente, $libres y $accion (HTML extra al pie) --}}
<article class="tarjeta tarjeta--habitacion">
    <div class="tarjeta__imagen">
        @if ($tipo->FotoUrl)
            <img src="{{ asset($tipo->FotoUrl) }}" alt="Habitación {{ $tipo->Nombre }}">
        @endif
    </div>
    <div class="tarjeta__cuerpo">
        <h3 class="tarjeta__titulo">{{ $tipo->Nombre }}</h3>
        <p class="tarjeta__texto">{{ $tipo->Descripcion }}</p>
        <ul class="datos">
            <li>Hasta {{ $tipo->Capacidad }} personas</li>
            @isset($libres)
                <li>{{ $libres }} {{ $libres == 1 ? 'habitación libre' : 'habitaciones libres' }}</li>
            @endisset
        </ul>
        <p class="precio">
            <span class="precio__monto">₡{{ number_format($tipo->PrecioNoche, 0, ',', '.') }}</span>
            <span class="precio__unidad">por noche</span>
        </p>
    </div>
    <div class="tarjeta__pie">
        {{ $accion ?? '' }}
    </div>
</article>

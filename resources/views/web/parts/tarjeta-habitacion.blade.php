{{-- Tarjeta de un tipo de habitación. Recibe $tipo y, opcionalmente, $libres y $accion (HTML extra al pie) --}}
<article class="card h-100 tarjeta-habitacion">
    @if ($tipo->FotoUrl)
        <img class="card-img-top" src="{{ asset($tipo->FotoUrl) }}" alt="Habitación {{ $tipo->Nombre }}" loading="lazy">
    @endif
    <div class="card-body d-flex flex-column">
        <h3 class="card-title mb-2">{{ $tipo->Nombre }}</h3>
        <p class="card-text">{{ $tipo->Descripcion }}</p>
        <ul class="datos">
            <li>Hasta {{ $tipo->Capacidad }} personas</li>
            @isset($libres)
                <li>{{ $libres }} {{ $libres == 1 ? 'habitación libre' : 'habitaciones libres' }}</li>
            @endisset
        </ul>
        <p class="precio mt-auto">
            <span class="precio__monto">₡{{ number_format($tipo->PrecioNoche, 0, ',', '.') }}</span>
            <span class="precio__unidad">por noche</span>
        </p>
    </div>
    @if (! empty($accion))
        <div class="card-footer px-4 pb-4">
            {{ $accion }}
        </div>
    @endif
</article>

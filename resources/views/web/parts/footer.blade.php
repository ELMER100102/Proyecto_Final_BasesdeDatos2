<footer class="pie">
    <div class="contenedor pie__contenido">
        <div>
            <strong class="pie__titulo">{{ $hotelSitio->Nombre ?? 'Hotel' }}</strong>
            <p>{{ $hotelSitio->Direccion ?? '' }}</p>
        </div>
        <div>
            <p>Tel. {{ $hotelSitio->Telefono ?? '' }}</p>
            <p>{{ $hotelSitio->Correo ?? '' }}</p>
        </div>
        <nav class="pie__menu" aria-label="Enlaces del pie">
            <a href="{{ route('habitaciones') }}">Habitaciones</a>
            <a href="{{ route('reservar') }}">Reservar</a>
            <a href="{{ route('contacto') }}">Contacto</a>
        </nav>
    </div>
</footer>
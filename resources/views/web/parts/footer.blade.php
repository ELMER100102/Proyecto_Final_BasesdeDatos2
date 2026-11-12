<footer class="pie">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-md-6 col-lg-5">
                <p class="pie__marca">{{ $hotelSitio->Nombre ?? 'Hotel' }}</p>
                <p class="mb-0">{{ $hotelSitio->Direccion ?? '' }}</p>
            </div>
            <div class="col-6 col-md-3 col-lg-4">
                <h2 class="pie__titulo">Contacto</h2>
                <ul>
                    @if ($hotelSitio?->Telefono)
                        <li><a href="tel:{{ preg_replace('/[^0-9+]/', '', $hotelSitio->Telefono) }}">Tel. {{ $hotelSitio->Telefono }}</a></li>
                    @endif
                    @if ($hotelSitio?->Correo)
                        <li><a class="text-break" href="mailto:{{ $hotelSitio->Correo }}">{{ $hotelSitio->Correo }}</a></li>
                    @endif
                </ul>
            </div>
            <nav class="col-6 col-md-3" aria-label="Enlaces del pie">
                <h2 class="pie__titulo">Enlaces</h2>
                <ul>
                    <li><a href="{{ route('habitaciones') }}">Habitaciones</a></li>
                    <li><a href="{{ route('reservar') }}">Reservar</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
            </nav>
        </div>
        <p class="pie__legal mb-0">© {{ date('Y') }} {{ $hotelSitio->Nombre ?? 'Hotel' }} · Puerto Viejo, Costa Rica</p>
    </div>
</footer>

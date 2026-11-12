<div class="card buscador">
    <div class="card-body p-3 p-md-4">
        <form method="GET" action="{{ route('reservar') }}" class="row g-2 g-md-3 align-items-end">
            <div class="col-12 col-md">
                <label class="form-label text-white" for="entrada">Llegada</label>
                <input class="form-control" type="date" id="entrada" name="entrada" min="{{ now()->toDateString() }}"
                    required>
            </div>
            <div class="col-12 col-md">
                <label class="form-label text-white" for="salida">Salida</label>
                <input class="form-control" type="date" id="salida" name="salida"
                    min="{{ now()->addDay()->toDateString() }}" required>
            </div>
            <div class="col-12 col-md-2">
                <label class="form-label text-white" for="personas">Personas</label>
                <input class="form-control" type="number" id="personas" name="personas" value="2" min="1"
                    max="10" required>
            </div>
            <div class="col-12 col-md-auto">
                <button class="btn btn-primary w-100">Ver disponibilidad</button>
            </div>

            <a href="#reserva-ancla" class="btn btn-primary w-100 reserva-fija__boton">Ver disponibilidad</a>

        </form>
    </div>
</div>

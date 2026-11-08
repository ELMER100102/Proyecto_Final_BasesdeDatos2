@if (session('ok') || session('error') || $errors->any())
    <div class="contenedor">
        @if (session('ok'))
            <div class="alerta alerta--exito" role="status">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alerta alerta--error" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alerta alerta--error" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
@endif

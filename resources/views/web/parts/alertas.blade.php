@if (session('ok') || session('error') || $errors->any())
    <div class="container pt-4">
        @if (session('ok'))
            <div class="alert alert-success" role="status">{{ session('ok') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
@endif

<nav class="navbar bg-white border-bottom px-4">
    <span class="navbar-text">@yield('titulo', 'Panel')</span>
    <div class="d-flex align-items-center gap-3">
        <span>{{ auth()->user()->persona->Nombre }} · <small class="text-muted">{{ auth()->user()->Rol }}</small></span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-secondary">Salir</button>
        </form>
    </div>
</nav>

{{-- Botón eliminar con confirmación. Recibe $ruta y opcionalmente $texto --}}
<form method="POST" action="{{ $ruta }}" class="d-inline" onsubmit="return confirm('¿Eliminar este registro?')">
    @csrf
    @method('DELETE')
    <button class="btn btn-sm btn-outline-danger">{{ $texto ?? 'Eliminar' }}</button>
</form>

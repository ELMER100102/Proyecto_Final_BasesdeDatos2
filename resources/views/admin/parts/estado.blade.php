{{-- Pastilla para estados de reserva y habitación. Recibe $estado.
     Clases en admin.css: .estado-pendiente, .estado-confirmada, .estado-en_curso, .estado-finalizada,
     .estado-cancelada, .estado-disponible, .estado-ocupada, .estado-sucia, .estado-mantenimiento --}}
<span class="badge estado estado-{{ strtolower($estado) }}">{{ str_replace('_', ' ', $estado) }}</span>

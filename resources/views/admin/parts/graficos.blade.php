{{-- Gráficos del dashboard (Chart.js). Recibe $graficos desde DashboardController. --}}
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header">Ingresos de los últimos 6 meses <span class="text-muted small">· SQL Server, sp_ReporteIngresos</span></div>
            <div class="card-body"><canvas id="graficoIngresos" height="220"></canvas></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card h-100">
            <div class="card-header">Habitaciones hoy <span class="text-muted small">· SQL Server</span></div>
            <div class="card-body"><canvas id="graficoHabitaciones" height="220"></canvas></div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="card h-100">
            <div class="card-header">Calificación promedio <span class="text-muted small">· MongoDB, aggregate</span></div>
            <div class="card-body"><canvas id="graficoCalificaciones" height="220"></canvas></div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('assets/js/chart.umd.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (typeof Chart === 'undefined') return;          // si falta el archivo, el panel sigue funcionando

    const datos   = @json($graficos);
    const colones = v => '₡' + Number(v).toLocaleString('es-CR', { maximumFractionDigits: 0 });
    const colores = {
        bosque: '#0d542b', musgo: '#008236', lima: '#b9ff78',
        coral: '#f54320', ceniza: '#d6d3d1', tinta: '#1c1917'
    };
    Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
    Chart.defaults.color = '#44403b';

    // 1. Ingresos por mes: barras apiladas (hospedaje + consumos)
    new Chart(document.getElementById('graficoIngresos'), {
        type: 'bar',
        data: {
            labels: datos.ingresos.meses,
            datasets: [
                { label: 'Hospedaje', data: datos.ingresos.hospedaje, backgroundColor: colores.bosque, borderRadius: 4 },
                { label: 'Consumos',  data: datos.ingresos.consumos,  backgroundColor: colores.lima,   borderRadius: 4 }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, ticks: { callback: colones } }
            },
            plugins: { tooltip: { callbacks: { label: c => c.dataset.label + ': ' + colones(c.parsed.y) } } }
        }
    });

    // 2. Estado de las habitaciones: dona
    const estados = { DISPONIBLE: colores.lima, OCUPADA: colores.bosque, SUCIA: colores.ceniza, MANTENIMIENTO: colores.coral };
    new Chart(document.getElementById('graficoHabitaciones'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(datos.habitaciones),
            datasets: [{
                data: Object.values(datos.habitaciones),
                backgroundColor: Object.keys(datos.habitaciones).map(e => estados[e] ?? colores.tinta),
                borderColor: '#fff', borderWidth: 2
            }]
        },
        options: { cutout: '60%', plugins: { legend: { position: 'bottom' } } }
    });

    // 3. Calificación promedio por tipo (MongoDB): barras horizontales de 0 a 5
    new Chart(document.getElementById('graficoCalificaciones'), {
        type: 'bar',
        data: {
            labels: Object.keys(datos.calificaciones),
            datasets: [{
                label: 'Promedio',
                data: Object.values(datos.calificaciones).map(c => c.promedio),
                backgroundColor: colores.musgo, borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            scales: { x: { min: 0, max: 5, ticks: { stepSize: 1 } }, y: { grid: { display: false } } },
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: c => {
                    const info = Object.values(datos.calificaciones)[c.dataIndex];
                    return '★ ' + info.promedio + ' (' + info.total + ' reseñas)';
                } } }
            }
        }
    });
});
</script>
@endpush

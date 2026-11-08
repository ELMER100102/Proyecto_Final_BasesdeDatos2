<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

//panel administrativo
// Administrador y recepcionista
Route::prefix('admin')->name('admin.')->middleware(['auth', 'rol:ADMINISTRADOR,RECEPCIONISTA'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Pendientes del equipo de operación:
    // Route::get('reservas', [ReservaController::class, 'index'])->name('reservas.index');
    // Route::post('reservas/{id}/check-in', [ReservaController::class, 'checkIn'])->name('reservas.checkin');
    // Route::post('reservas/{id}/check-out', [ReservaController::class, 'checkOut'])->name('reservas.checkout');
    // Route::post('reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
    // Route::get('reporte', [ReporteController::class, 'index'])->name('reporte');

    // Solo administrador
    Route::middleware('rol:ADMINISTRADOR')->group(function () {
        // Pendientes del equipo de configuración:
        // Route::resource('tipos', TipoHabitacionController::class);
        // Route::resource('habitaciones', HabitacionController::class);
        // Route::resource('servicios', ServicioController::class);
    });
});

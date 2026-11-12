<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HabitacionController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\HuespedController;
use App\Http\Controllers\Admin\ImagenController;
use App\Http\Controllers\Admin\MensajeController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\ResenaController;
use App\Http\Controllers\Admin\ReservaController;
use App\Http\Controllers\Admin\ServicioController;
use App\Http\Controllers\Admin\TipoHabitacionController;
use App\Http\Controllers\Admin\UsuarioController;
use Illuminate\Support\Facades\Route;

//PANEL ADMINISTRAIVO DEL ADMIN

Route::prefix('admin')->name('admin.')->middleware(['auth', 'rol:ADMINISTRADOR,RECEPCIONISTA'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    //Operación: administrador y recepcionista
    Route::get('reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('reservas/nueva', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('reservas/{id}', [ReservaController::class, 'show'])->name('reservas.show');
    Route::post('reservas/{id}/check-in', [ReservaController::class, 'checkIn'])->name('reservas.checkin');
    Route::post('reservas/{id}/cargo', [ReservaController::class, 'cargo'])->name('reservas.cargo');
    Route::post('reservas/{id}/check-out', [ReservaController::class, 'checkOut'])->name('reservas.checkout');
    Route::post('reservas/{id}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');

    Route::get('huespedes', [HuespedController::class, 'index'])->name('huespedes.index');
    Route::get('huespedes/nuevo', [HuespedController::class, 'create'])->name('huespedes.create');
    Route::post('huespedes', [HuespedController::class, 'store'])->name('huespedes.store');
    Route::get('huespedes/{id}', [HuespedController::class, 'show'])->name('huespedes.show');

    Route::get('habitaciones-estado', [HabitacionController::class, 'index'])->name('habitaciones.estado.index');
    Route::post('habitaciones/{habitacion}/estado', [HabitacionController::class, 'estado'])->name('habitaciones.estado');

    Route::get('reporte', [ReporteController::class, 'index'])->name('reporte');

    // Configuración: solo administrador
    Route::middleware('rol:ADMINISTRADOR')->group(function () {
        Route::resource('tipos', TipoHabitacionController::class)->except('show')
            ->parameters(['tipos' => 'tipo']);
        Route::resource('habitaciones', HabitacionController::class)->except('show')
            ->parameters(['habitaciones' => 'habitacion']);
        Route::resource('servicios', ServicioController::class)->except('show')
            ->parameters(['servicios' => 'servicio']);
        Route::resource('imagenes', ImagenController::class)->except('show')
            ->parameters(['imagenes' => 'imagen']);
        Route::resource('usuarios', UsuarioController::class)->except(['show', 'destroy'])
            ->parameters(['usuarios' => 'usuario']);

        Route::get('hotel', [HotelController::class, 'edit'])->name('hotel.edit');
        Route::put('hotel', [HotelController::class, 'update'])->name('hotel.update');

        Route::get('resenas', [ResenaController::class, 'index'])->name('resenas.index');
        Route::post('resenas/{id}/responder', [ResenaController::class, 'responder'])->name('resenas.responder');
        Route::delete('resenas/{id}', [ResenaController::class, 'destroy'])->name('resenas.destroy');

        Route::get('mensajes', [MensajeController::class, 'index'])->name('mensajes.index');
        Route::post('mensajes/{id}/marcar', [MensajeController::class, 'marcar'])->name('mensajes.marcar');
        Route::delete('mensajes/{id}', [MensajeController::class, 'destroy'])->name('mensajes.destroy');
    });
});

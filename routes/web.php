<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\ContactoController;
use App\Http\Controllers\Web\CuentaController;
use App\Http\Controllers\Web\HabitacionController;
use App\Http\Controllers\Web\InicioController;
use App\Http\Controllers\Web\ReservaController;
use Illuminate\Support\Facades\Route;

//SITIO PUBLICO
Route::get('/', [InicioController::class, 'index'])->name('inicio');
Route::get('habitaciones', [HabitacionController::class, 'index'])->name('habitaciones');
Route::get('habitaciones/{id}', [HabitacionController::class, 'show'])->name('habitaciones.show');
Route::get('reservar', [ReservaController::class, 'create'])->name('reservar');
Route::get('contacto', [ContactoController::class, 'create'])->name('contacto');
Route::post('contacto', [ContactoController::class, 'store'])->name('contacto.enviar');

//INICIAR SESION
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('registro', [AuthController::class, 'registro']);
});
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

//AREA DEL HUESPED
Route::middleware(['auth', 'rol:HUESPED'])->group(function () {
    Route::post('reservar', [ReservaController::class, 'store'])->name('reservar.guardar');
    Route::get('mis-reservas', [CuentaController::class, 'misReservas'])->name('mis-reservas');
    Route::post('mis-reservas/{id}/cancelar', [CuentaController::class, 'cancelar'])->name('mis-reservas.cancelar');
    Route::get('mis-reservas/{id}/resena', [CuentaController::class, 'crearResena'])->name('resena.crear');
    Route::post('mis-reservas/{id}/resena', [CuentaController::class, 'guardarResena'])->name('resena.guardar');
});

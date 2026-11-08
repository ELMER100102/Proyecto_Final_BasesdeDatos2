<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\InicioController;
use Illuminate\Support\Facades\Route;

//Sitio web público, sin autenticación
Route::get('/', [InicioController::class, 'index'])->name('inicio');

// Pendientes del equipo Web:
// Route::get('habitaciones', [HabitacionController::class, 'index'])->name('habitaciones');
// Route::get('reservar', [ReservaController::class, 'create'])->name('reservar');
// Route::get('contacto', [ContactoController::class, 'create'])->name('contacto');

//Inicio de sesion y registro de usuarios
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('registro', [AuthController::class, 'registro']);
});

Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

//area huesped
Route::middleware(['auth', 'rol:HUESPED'])->group(function () {
    // Route::get('mis-reservas', [CuentaController::class, 'index'])->name('mis-reservas');
});

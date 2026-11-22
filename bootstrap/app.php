<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // Rutas del panel administrativo en su propio archivo
        then: function () {
            Route::middleware('web')->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias para usar ->middleware('rol:ADMINISTRADOR,RECEPCIONISTA')
        $middleware->alias([
            'rol' => \App\Http\Middleware\VerificarRol::class,
        ]);

        // Si alguien sin sesión entra a una ruta protegida, va al login
        $middleware->redirectGuestsTo(fn () => route('login'));

        // Middleware que le dice a SQL Server qué usuario de la aplicación está haciendo la petición
        $middleware->web(append: [\App\Http\Middleware\UsuarioEnSql::class]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

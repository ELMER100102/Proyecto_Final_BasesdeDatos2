<?php

namespace App\Providers;

use App\Models\Hotel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Datos del hotel disponibles en el layout del sitio (encabezado y pie) y en contacto.
        // Se consulta una sola vez por petición.
        $hotel = null;
        // View::composer(['layouts.web', 'web.contacto'], function ($view) use (&$hotel) {
        View::composer(['layouts.web', 'web.*'], function ($view) use (&$hotel) {
        $hotel ??= Hotel::first();
            $view->with('hotelSitio', $hotel);
        });
    }
}

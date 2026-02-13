<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

/**
 * AppServiceProvider - Proveedor de servicios principal
 * 
 * Registra configuraciones globales de la aplicación.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Registra cualquier servicio de la aplicación
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services
     */
    public function boot(): void
    {
        // Usar Bootstrap 5 para la paginación
        Paginator::useBootstrapFive();
    }
}

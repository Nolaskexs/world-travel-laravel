<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Registrar servicios de Laravel.
     */
    public function register(): void
    {
        //
    }

    /**
     * Configurar permisos de World Travel.
     */
    public function boot(): void
    {
        // Solo los usuarios con rol admin
        // pueden acceder a los CRUD administrativos.
        Gate::define('administrar', function (User $user) {
            return $user->rol === 'admin';
        });
    }
}

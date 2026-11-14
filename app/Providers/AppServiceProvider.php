<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Destino;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
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
            return $user->esAdmin();
        });

        // Compartir catálogo de destinos con las vistas que tienen buscador
        View::composer(['inicio', 'search'], function ($view) {
            try {
                if (Schema::hasTable('destinos')) {
                    $destinos = Destino::select('id', 'nombre', 'codigo_iata', 'aeropuerto', 'pais')
                        ->orderBy('nombre')
                        ->get();
                    $destinosData = $destinos->map(function ($d) {
                        return [
                            $d->nombre,
                            $d->codigo_iata ?: strtoupper(substr($d->nombre, 0, 3)),
                            $d->pais,
                            $d->aeropuerto ?: $d->nombre,
                        ];
                    });
                    $view->with('destinosCatalogo', $destinosData);
                }
            } catch (\Throwable $e) {
                // Ignorar si la base de datos no está disponible en este momento
            }
        });
    }
}

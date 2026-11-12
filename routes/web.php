<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogoViajesController;
use App\Http\Controllers\ItinerarioController;

// 1. Ruta raíz limpia y con nombre
Route::view('/', 'inicio')->name('inicio');

// 2. Vistas estáticas
Route::view('/login', 'login')->name('login');
Route::view('/registro', 'register')->name('register');
Route::view('/buscar', 'search')->name('search');

// 3. Vistas con controladores
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// 4. Alojamientos
Route::view('/alojamientos', 'alojamientos')->name('alojamientos');
Route::get('/alojamientos/{codigo}', function (string $codigo) {
    return view('detalle-alojamiento', compact('codigo'));
})->whereIn('codigo', ['cusco', 'lima', 'arequipa'])->name('alojamientos.detalle');

// 5. Panel de administración
Route::view('/admin', 'dashboard-admin')->name('admin.dashboard');
//6. Confirmación de reserva de alojamiento
Route::view('/reservas/confirmar', 'confirmar-reserva')
    ->name('reservas.confirmar');

// 7. Autos (alquiler de autos)
Route::view('/autos', 'autos')->name('autos');

// Paquetes turísticos y ofertas
Route::get('/paquetes', [CatalogoViajesController::class, 'paquetes'])
    ->name('paquetes');

Route::get('/paquetes/{paquete}', [CatalogoViajesController::class, 'detalle'])
    ->name('paquetes.detalle');

Route::get('/ofertas', [CatalogoViajesController::class, 'ofertas'])
    ->name('ofertas');

Route::middleware('auth')->group(function () {

    Route::get('/itinerarios', [ItinerarioController::class, 'index'])
        ->name('itinerarios.index');

    Route::post('/itinerarios', [ItinerarioController::class, 'store'])
        ->name('itinerarios.store');

});


/*
|--------------------------------------------------------------------------
| Vista previa de Itinerarios - Solo desarrollo local
|--------------------------------------------------------------------------
| Permite probar el diseño sin iniciar sesión.
| No muestra datos reales ni permite guardar itinerarios.
*/

if (app()->environment('local')) {
    Route::get('/vista-previa-itinerarios', function () {
        return view('itinerarios', [
            'itinerarios' => collect(),
        ]);
    })->name('itinerarios.preview');
}

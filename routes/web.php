<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogoViajesController;
use App\Http\Controllers\ItinerarioController;

// 1. Ruta raíz limpia y con nombre
Route::view('/', 'inicio')->name('inicio');

// 2. Vistas estáticas
Route::view('/buscar', 'search')->name('search');
Route::view('/ayuda', 'ayuda')->name('ayuda');
// (No agregues Route::view('/login' aquí, Fortify ya lo hace automáticamente)

// 3. Panel principal del Cliente
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// 4. Panel de Administración (Protección total)
Route::get('/admin', function () {
    // Si el usuario logueado NO es admin, lo rebotamos a su dashboard de cliente
    if (auth()->user()->rol !== 'admin') {
        return redirect('/dashboard');
    }
    
    return view('dashboard-admin');
})->middleware('auth');

// 5. Alojamientos
Route::view('/alojamientos', 'alojamientos')->name('alojamientos');
Route::get('/alojamientos/{codigo}', function (string $codigo) {
    return view('detalle-alojamiento', compact('codigo'));
})->whereIn('codigo', ['cusco', 'lima', 'arequipa'])->name('alojamientos.detalle');

// 6. Confirmación de reserva de alojamiento
Route::view('/reservas/confirmar', 'confirmar-reserva')->name('reservas.confirmar');

// 7. Autos (alquiler de autos)
Route::view('/autos', 'autos')->name('autos');

// 8. Paquetes turísticos y ofertas
Route::get('/paquetes', [CatalogoViajesController::class, 'paquetes'])->name('paquetes');
Route::get('/paquetes/{paquete}', [CatalogoViajesController::class, 'detalle'])->name('paquetes.detalle');
Route::get('/ofertas', [CatalogoViajesController::class, 'ofertas'])->name('ofertas');

// 9. Itinerarios protegidos
Route::middleware('auth')->group(function () {
    Route::get('/itinerarios', [ItinerarioController::class, 'index'])->name('itinerarios.index');
    Route::post('/itinerarios', [ItinerarioController::class, 'store'])->name('itinerarios.store');
});

/*
|--------------------------------------------------------------------------
| Vista previa de Itinerarios - Solo desarrollo local
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/vista-previa-itinerarios', function () {
        return view('itinerarios', [
            'itinerarios' => collect(),
        ]);
    })->name('itinerarios.preview');
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogoViajesController;
use App\Http\Controllers\ItinerarioController;
use App\Http\Controllers\Admin\PaqueteController;

/*
|--------------------------------------------------------------------------
| 1. Página principal
|--------------------------------------------------------------------------
*/

Route::view('/', 'inicio')->name('inicio');

/*
|--------------------------------------------------------------------------
| 2. Páginas informativas
|--------------------------------------------------------------------------
*/

Route::view('/buscar', 'search')->name('search');

Route::view('/ayuda', 'ayuda')->name('ayuda');

// IMPORTANTE:
// No agregamos rutas de login, logout o registro.
// Laravel Fortify ya las administra.

/*
|--------------------------------------------------------------------------
| 3. Dashboard del cliente
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [
    DashboardController::class,
    'index'
])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| 4. Panel principal del administrador
|--------------------------------------------------------------------------
| Conservamos el comportamiento que implementó Jair.
*/

Route::get('/admin', function () {

    // Los clientes no pueden ingresar al panel.
    if (auth()->user()->rol !== 'admin') {
        return redirect()->route('dashboard');
    }

    return view('dashboard-admin');

})
    ->middleware('auth')
    ->name('admin.dashboard');

/*
|--------------------------------------------------------------------------
| 5. CRUD de paquetes turísticos
|--------------------------------------------------------------------------
| Solo los administradores pueden acceder.
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'can:administrar'])
    ->group(function () {

        // READ - Listar paquetes
        Route::get('/paquetes', [
            PaqueteController::class,
            'index'
        ])->name('paquetes.index');

        // CREATE - Mostrar formulario
        Route::get('/paquetes/crear', [
            PaqueteController::class,
            'create'
        ])->name('paquetes.create');

        // CREATE - Guardar en MySQL
        Route::post('/paquetes', [
            PaqueteController::class,
            'store'
        ])->name('paquetes.store');

        // UPDATE - Mostrar formulario de edición
        Route::get('/paquetes/{paquete}/editar', [
            PaqueteController::class,
            'edit'
        ])->name('paquetes.edit');

        // UPDATE - Guardar modificaciones
        Route::put('/paquetes/{paquete}', [
            PaqueteController::class,
            'update'
        ])->name('paquetes.update');

    });

/*
|--------------------------------------------------------------------------
| 6. Alojamientos
|--------------------------------------------------------------------------
*/

Route::view('/alojamientos', 'alojamientos')
    ->name('alojamientos');

Route::get('/alojamientos/{codigo}', function (string $codigo) {

    return view('detalle-alojamiento', compact('codigo'));

})
    ->whereIn('codigo', [
        'cusco',
        'lima',
        'arequipa'
    ])
    ->name('alojamientos.detalle');

/*
|--------------------------------------------------------------------------
| 7. Confirmación de reserva de alojamiento
|--------------------------------------------------------------------------
*/

Route::view('/reservas/confirmar', 'confirmar-reserva')
    ->name('reservas.confirmar');

/*
|--------------------------------------------------------------------------
| 8. Alquiler de autos
|--------------------------------------------------------------------------
*/

Route::view('/autos', 'autos')->name('autos');

/*
|--------------------------------------------------------------------------
| 9. Catálogo público de paquetes y ofertas
|--------------------------------------------------------------------------
*/

Route::get('/paquetes', [
    CatalogoViajesController::class,
    'paquetes'
])->name('paquetes');

Route::get('/paquetes/{paquete}', [
    CatalogoViajesController::class,
    'detalle'
])->name('paquetes.detalle');

Route::get('/ofertas', [
    CatalogoViajesController::class,
    'ofertas'
])->name('ofertas');

/*
|--------------------------------------------------------------------------
| 10. Itinerarios de usuarios autenticados
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/itinerarios', [
        ItinerarioController::class,
        'index'
    ])->name('itinerarios.index');

    Route::post('/itinerarios', [
        ItinerarioController::class,
        'store'
    ])->name('itinerarios.store');

});

/*
|--------------------------------------------------------------------------
| 11. Vista previa de itinerarios
|--------------------------------------------------------------------------
| Disponible solamente en el entorno local.
*/

if (app()->environment('local')) {

    Route::get('/vista-previa-itinerarios', function () {

        return view('itinerarios', [
            'itinerarios' => collect(),
        ]);

    })->name('itinerarios.preview');

}

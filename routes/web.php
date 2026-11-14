
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogoViajesController;
use App\Http\Controllers\ItinerarioController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\ReservaClienteController;
use App\Http\Controllers\Admin\PaqueteController;
use App\Http\Controllers\Admin\DestinoController;
use App\Http\Controllers\Admin\FacturacionController;
use App\Http\Controllers\Admin\AjustesController;

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

Route::get('/buscar', [BuscadorController::class, 'index'])->name('search');

Route::view('/ayuda', 'ayuda')->name('ayuda');

Route::get('/libro-de-reclamaciones', function () {
    return view('reclamaciones');
})->name('reclamaciones');

Route::post('/libro-de-reclamaciones', function (Request $request) {
    return back()->with('success', 'Tu hoja de reclamación ha sido registrada correctamente.');
})->name('reclamaciones.store');

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
    if (!auth()->user()->esAdmin()) {
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


        // DELETE: Eliminar paquete turístico
        Route::delete('/paquetes/{paquete}', [
            PaqueteController::class,
            'destroy'
        ])->name('paquetes.destroy');

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
| 7. Proceso principal: Confirmación y registro de reserva en la BD
|--------------------------------------------------------------------------
*/

Route::get('/reservas/confirmar', [ReservaClienteController::class, 'create'])
    ->name('reservas.confirmar');

Route::post('/reservas', [ReservaClienteController::class, 'store'])
    ->name('reservas.store');

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

/*
|--------------------------------------------------------------------------
| Secciones adicionales del panel administrativo
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'can:administrar'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

       
// RESERVAS: Mostrar listado, búsqueda y filtros.
Route::get(
    '/reservas',
    [\App\Http\Controllers\Admin\ReservaController::class, 'index']
)->name('reservas.index');

// RESERVAS: Mostrar detalles de una reserva.
Route::get(
    '/reservas/{reserva}',
    [\App\Http\Controllers\Admin\ReservaController::class, 'show']
)->name('reservas.show');

// RESERVAS: Actualizar el estado.
Route::patch(
    '/reservas/{reserva}/estado',
    [\App\Http\Controllers\Admin\ReservaController::class, 'update']
)->name('reservas.update');



        // CLIENTES: Listar y buscar.
        Route::get(
            '/clientes',
            [\App\Http\Controllers\Admin\ClienteController::class, 'index']
        )->name('clientes.index');

        // CLIENTES: Ver detalles.
        Route::get(
            '/clientes/{cliente}',
            [\App\Http\Controllers\Admin\ClienteController::class, 'show']
        )->name('clientes.show');


        // DESTINOS: CRUD completo
        Route::get('/destinos', [DestinoController::class, 'index'])->name('destinos.index');
        Route::get('/destinos/crear', [DestinoController::class, 'create'])->name('destinos.create');
        Route::post('/destinos', [DestinoController::class, 'store'])->name('destinos.store');
        Route::get('/destinos/{destino}/editar', [DestinoController::class, 'edit'])->name('destinos.edit');
        Route::put('/destinos/{destino}', [DestinoController::class, 'update'])->name('destinos.update');
        Route::delete('/destinos/{destino}', [DestinoController::class, 'destroy'])->name('destinos.destroy');

        // FACTURACIÓN: Listado y balance financiero
        Route::get('/facturacion', [FacturacionController::class, 'index'])->name('facturacion.index');

        // AJUSTES: Configuración del sistema y de la agencia
        Route::get('/ajustes', [AjustesController::class, 'index'])->name('ajustes.index');
        Route::put('/ajustes', [AjustesController::class, 'update'])->name('ajustes.update');

    });

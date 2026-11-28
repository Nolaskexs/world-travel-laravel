
<?php

use App\Http\Controllers\Admin\AjustesController;
use App\Http\Controllers\Admin\AlojamientoController as AdminAlojamientoController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DestinoController;
use App\Http\Controllers\Admin\FacturacionController;
use App\Http\Controllers\Admin\PaqueteController;
use App\Http\Controllers\Admin\ReclamacionController as AdminReclamacionController;
use App\Http\Controllers\Admin\ReservaController;
use App\Http\Controllers\AlojamientoController;
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\BuscadorController;
use App\Http\Controllers\CatalogoViajesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItinerarioController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReclamacionController;
use App\Http\Controllers\ReservaClienteController;
use App\Models\Paquete;
use Illuminate\Support\Facades\Route;

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

Route::view('/nosotros', 'nosotros')->name('nosotros');

Route::view('/preguntas-frecuentes', 'preguntas-frecuentes')
    ->name('preguntas.frecuentes');

Route::view('/terminos-y-condiciones', 'terminos')
    ->name('terminos');

Route::view('/politica-de-privacidad', 'privacidad')
    ->name('privacidad');

Route::get('/libro-de-reclamaciones', [ReclamacionController::class, 'create'])->name('reclamaciones');
Route::post('/libro-de-reclamaciones', [ReclamacionController::class, 'store'])->name('reclamaciones.store');

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
    'index',
])
    ->middleware('auth')
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| 4. Panel principal del administrador
|--------------------------------------------------------------------------
| Conservamos el comportamiento que implementó Jair.
*/

Route::get('/admin', [AdminDashboardController::class, 'index'])
    ->middleware(['auth', 'can:administrar'])
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
            'index',
        ])->name('paquetes.index');

        // CREATE - Mostrar formulario
        Route::get('/paquetes/crear', [
            PaqueteController::class,
            'create',
        ])->name('paquetes.create');

        // CREATE - Guardar en MySQL
        Route::post('/paquetes', [
            PaqueteController::class,
            'store',
        ])->name('paquetes.store');

        // UPDATE - Mostrar formulario de edición
        Route::get('/paquetes/{paquete}/editar', [
            PaqueteController::class,
            'edit',
        ])->name('paquetes.edit');

        // UPDATE - Guardar modificaciones
        Route::put('/paquetes/{paquete}', [
            PaqueteController::class,
            'update',
        ])->name('paquetes.update');

        // DELETE: Eliminar paquete turístico
        Route::delete('/paquetes/{paquete}', [
            PaqueteController::class,
            'destroy',
        ])->name('paquetes.destroy');

    });

/*
|--------------------------------------------------------------------------
| 6. Alojamientos (Catálogo público y detalle desde MySQL)
|--------------------------------------------------------------------------
*/

Route::get('/alojamientos', [AlojamientoController::class, 'index'])
    ->name('alojamientos');

Route::get('/alojamientos/{codigo}', [AlojamientoController::class, 'show'])
    ->where('codigo', '[a-zA-Z0-9\-_]+')
    ->name('alojamientos.detalle');

/*
|--------------------------------------------------------------------------
| 7. Selección de Asientos y Check-in (Requiere autenticación de Cliente o Admin)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/asientos', [AsientoController::class, 'index'])->name('asientos.index');
    Route::post('/asientos', [AsientoController::class, 'guardar'])->name('asientos.guardar');
    Route::get('/checkin/{reserva}', function ($reserva) {
        return redirect()->route('asientos.index', ['reserva_id' => $reserva]);
    })->name('asientos.checkin');

    /*
    |--------------------------------------------------------------------------
    | 8. Proceso principal: Confirmación y registro de reserva en la BD
    |--------------------------------------------------------------------------
    */
    Route::get('/reservas/confirmar', [ReservaClienteController::class, 'create'])
        ->name('reservas.confirmar');

    Route::post('/reservas', [ReservaClienteController::class, 'store'])
        ->name('reservas.store');

    // Flujos independientes: cada servicio tiene su propio formulario y registro.
    Route::get('/reservas/confirmar/alojamientos', [ReservaClienteController::class, 'confirmarAlojamiento'])
        ->name('reservas.confirmar.alojamientos');
    Route::post('/reservas/alojamientos', [ReservaClienteController::class, 'guardarAlojamiento'])
        ->name('reservas.alojamientos.store');

    Route::get('/reservas/confirmar/vuelos', [ReservaClienteController::class, 'confirmarVuelo'])
        ->name('reservas.confirmar.vuelos');
    Route::post('/reservas/vuelos', [ReservaClienteController::class, 'guardarVuelo'])
        ->name('reservas.vuelos.store');

    Route::get('/reservas/confirmar/ofertas', [ReservaClienteController::class, 'confirmarOferta'])
        ->name('reservas.confirmar.ofertas');
    Route::post('/reservas/ofertas', [ReservaClienteController::class, 'guardarOferta'])
        ->name('reservas.ofertas.store');

    Route::get('/reservas/exito/{reserva}', [ReservaClienteController::class, 'exito'])
        ->name('reservas.exito');
});

/*
|--------------------------------------------------------------------------
| 8. Catálogo público de paquetes y ofertas
|--------------------------------------------------------------------------
*/

Route::get('/paquetes', [
    CatalogoViajesController::class,
    'paquetes',
])->name('paquetes');

Route::get('/paquetes/{paquete}', [
    CatalogoViajesController::class,
    'detalle',
])->name('paquetes.detalle');

Route::get('/ofertas', [
    CatalogoViajesController::class,
    'ofertas',
])->name('ofertas');

Route::get('/ofertas/{oferta}', [
    CatalogoViajesController::class,
    'detalleOferta',
])->name('ofertas.detalle');

/*
|--------------------------------------------------------------------------
| 10. Itinerarios de usuarios autenticados
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/itinerarios', [
        ItinerarioController::class,
        'index',
    ])->name('itinerarios.index');

    Route::post('/itinerarios', [
        ItinerarioController::class,
        'store',
    ])->name('itinerarios.store');

});

Route::get('/pagos/{reserva}', [PagoController::class, 'show'])
    ->middleware('auth')
    ->name('pagos.show');

Route::post(
    '/pagos/{reserva}/simular',
    [PagoController::class, 'simular']
)->middleware('auth')->name('pagos.simular');

Route::get(
    '/pagos/comprobante/{pago}',
    [PagoController::class, 'comprobante']
)->middleware('auth')->name('pagos.comprobante');

/*
|--------------------------------------------------------------------------
| 11. Vista previa de itinerarios
|--------------------------------------------------------------------------
| Disponible solamente en el entorno local.
*/

if (app()->environment('local')) {

    Route::get('/vista-previa-itinerarios', function () {

        $catalogoDestinos = Paquete::where('activo', true)
            ->select('destino as ciudad', 'pais', 'precio as costo_referencial', 'imagen_url')
            ->orderBy('destino')
            ->get()
            ->map(function ($p) {
                $img = $p->imagen_url;
                $url = ! empty($img) ? (str_starts_with($img, 'http') ? $img : asset(ltrim($img, '/'))) : asset('icons/MachuPicchu1-Carrusel.jpg');

                return [
                    'ciudad' => $p->ciudad,
                    'pais' => $p->pais,
                    'costo' => (float) $p->costo_referencial,
                    'imagen' => $url,
                ];
            })
            ->unique('ciudad')
            ->values();

        return view('itinerarios', [
            'itinerarios' => collect(),
            'catalogoDestinos' => $catalogoDestinos,
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

        // RESERVAS: Resumen general con acceso a las cuatro subsecciones.
        Route::get(
            '/reservas',
            [ReservaController::class, 'index']
        )->name('reservas.index');

        // RESERVAS: Subsecciones independientes por tipo_servicio.
        Route::get(
            '/reservas/vuelos',
            [ReservaController::class, 'vuelos']
        )->name('reservas.vuelos');

        Route::get(
            '/reservas/alojamientos',
            [ReservaController::class, 'alojamientos']
        )->name('reservas.alojamientos');

        Route::get(
            '/reservas/paquetes',
            [ReservaController::class, 'paquetes']
        )->name('reservas.paquetes');

        Route::get(
            '/reservas/ofertas',
            [ReservaController::class, 'ofertas']
        )->name('reservas.ofertas');

        // RESERVAS: Mostrar detalles de una reserva.
        Route::get(
            '/reservas/{reserva}',
            [ReservaController::class, 'show']
        )->name('reservas.show');

        // RESERVAS: Actualizar el estado.
        Route::patch(
            '/reservas/{reserva}/estado',
            [ReservaController::class, 'update']
        )->name('reservas.update');

        // CLIENTES: Listar y buscar.
        Route::get(
            '/clientes',
            [ClienteController::class, 'index']
        )->name('clientes.index');

        // CLIENTES: Ver detalles.
        Route::get(
            '/clientes/{cliente}',
            [ClienteController::class, 'show']
        )->name('clientes.show');

        // DESTINOS: CRUD completo
        Route::get('/destinos', [DestinoController::class, 'index'])->name('destinos.index');
        Route::get('/destinos/crear', [DestinoController::class, 'create'])->name('destinos.create');
        Route::post('/destinos', [DestinoController::class, 'store'])->name('destinos.store');
        Route::get('/destinos/{destino}/editar', [DestinoController::class, 'edit'])->name('destinos.edit');
        Route::put('/destinos/{destino}', [DestinoController::class, 'update'])->name('destinos.update');
        Route::delete('/destinos/{destino}', [DestinoController::class, 'destroy'])->name('destinos.destroy');

        // ALOJAMIENTOS: CRUD completo de hoteles
        Route::resource('alojamientos', AdminAlojamientoController::class);

        // FACTURACIÓN: Listado y balance financiero
        Route::get('/facturacion', [
            FacturacionController::class,
            'index',
        ])->name('facturacion.index');

        // FACTURACIÓN: Ver comprobante de pago simulado
        Route::get('/facturacion/comprobante/{pago}', [
            FacturacionController::class,
            'comprobante',
        ])->name('facturacion.comprobante');

        // RECLAMACIONES: Hoja de reclamaciones y atención al cliente
        Route::get('/reclamaciones', [AdminReclamacionController::class, 'index'])->name('reclamaciones.index');
        Route::get('/reclamaciones/{id}', [AdminReclamacionController::class, 'show'])->name('reclamaciones.show');
        Route::put('/reclamaciones/{id}', [AdminReclamacionController::class, 'update'])->name('reclamaciones.update');

        // AJUSTES: Configuración del sistema y de la agencia
        Route::get('/ajustes', [AjustesController::class, 'index'])->name('ajustes.index');
        Route::put('/ajustes', [AjustesController::class, 'update'])->name('ajustes.update');

    });

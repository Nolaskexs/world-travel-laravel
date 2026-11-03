<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $usuario = [
            'nombre'        => 'Jair Rodrigo Nolasco Irazabal',
            'primer_nombre' => 'Jair',
            'iniciales'     => 'JN',
            'avatar'        => 'img/avatars/jair.jpg',
            'progreso'      => 72,
        ];

        $metricas = [
            ['label' => 'Vuelos Activos',      'valor' => 1, 'icono' => 'plane',    'tono' => 'blue'],
            ['label' => 'Check-in Disponible', 'valor' => 2, 'icono' => 'check',    'tono' => 'green'],
            ['label' => 'Reservas Pendientes', 'valor' => 1, 'icono' => 'calendar', 'tono' => 'purple'],
        ];

        $viajes = [
            [
                'titulo' => 'Vuelo a Roma (ROM)', 'fecha' => '30 nov 2026',
                'estado' => 'confirmado', 'estado_label' => 'Confirmado',
                'destacado' => 'US$ 1.230', 'nota' => null,
                'imagen' => 'img/viajes/roma.jpg',
            ],
            [
                'titulo' => 'Vuelo a Tokio (TKY)', 'fecha' => '15 dic 2026',
                'estado' => 'checkin', 'estado_label' => 'Check-in Disponible',
                'destacado' => '15 dic 2026', 'nota' => 'Stock limitado',
                'imagen' => 'img/viajes/tokio.jpg',
            ],
            [
                'titulo' => 'Lima a Cusco (CUS)', 'fecha' => '20 dic 2026',
                'estado' => 'pendiente', 'estado_label' => 'Pendiente de pago',
                'destacado' => '20 dic 2026', 'nota' => null,
                'imagen' => 'img/viajes/cusco.jpg',
            ],
            [
                'titulo' => 'Lima a Cancún (CUN) - Paquete todo incluido familiar', 'fecha' => '05 ene 2027',
                'estado' => 'confirmado', 'estado_label' => 'Confirmado',
                'destacado' => 'US$ 2.480', 'nota' => '4 pasajeros',
                'imagen' => 'img/viajes/cancun.jpg',
            ],
        ];

        $historial = [
            [
                'pasajero' => 'Jair Rodrigo Nolasco Irazabal', 'iniciales' => 'JN', 'avatar' => 'img/avatars/jair.jpg',
                'fecha' => '30/11/2026', 'tipo' => 'Internacional',
                'origen' => 'LIM', 'origen_ciudad' => 'Lima', 'destino' => 'CUN', 'destino_ciudad' => 'Cancún',
                'accion_label' => 'Ver boleto',
            ],
            [
                'pasajero' => 'Jair Rodrigo Nolasco Irazabal', 'iniciales' => 'JN', 'avatar' => 'img/avatars/jair.jpg',
                'fecha' => '20/12/2026', 'tipo' => 'Nacional',
                'origen' => 'LIM', 'origen_ciudad' => 'Lima', 'destino' => 'CUS', 'destino_ciudad' => 'Cusco',
                'accion_label' => 'Detalles',
            ],
            [
                'pasajero' => 'Prashant Venkataraman Subramaniam Krishnamurthy', 'iniciales' => 'PV', 'avatar' => 'img/avatars/prashant.jpg',
                'fecha' => '15/12/2026', 'tipo' => 'Internacional',
                'origen' => 'LIM', 'origen_ciudad' => 'Lima', 'destino' => 'NRT', 'destino_ciudad' => 'Tokio (Aeropuerto Internacional de Narita)',
                'accion_label' => 'Ver boleto',
            ],
        ];

        $frecuentes = [
            ['nombre' => 'Jair Rodrigo', 'iniciales' => 'JR', 'avatar' => 'img/avatars/jair.jpg',     'estado' => 'Estado de vuelo'],
            ['nombre' => 'Prashant',     'iniciales' => 'PR', 'avatar' => 'img/avatars/prashant.jpg', 'estado' => 'Estado de vuelo'],
            ['nombre' => 'Ravi',         'iniciales' => 'RA', 'avatar' => 'img/avatars/ravi.jpg',     'estado' => 'Estado de vuelo'],
        ];

        $millas = [
            ['mes' => 'Jul', 'nacional' => 1200, 'internacional' => 800],
            ['mes' => 'Ago', 'nacional' => 1800, 'internacional' => 1400],
            ['mes' => 'Sep', 'nacional' => 2100, 'internacional' => 1700],
            ['mes' => 'Oct', 'nacional' => 2600, 'internacional' => 1900],
            ['mes' => 'Nov', 'nacional' => 2300, 'internacional' => 1500],
        ];

        $tripulacion = [
            ['nombre' => 'Soporte 24/7',           'iniciales' => 'S',  'avatar' => 'img/avatars/piloto-1.jpg', 'rol' => 'Soporte',      'accion' => 'Contactar'],
            ['nombre' => 'Asistencia en Aeropuerto', 'iniciales' => 'AA', 'avatar' => 'img/avatars/piloto-2.jpg', 'rol' => 'Aeropuerto', 'accion' => 'Seguir'],
        ];

        return view('dashboard', compact(
            'usuario', 'metricas', 'viajes', 'historial', 'frecuentes', 'millas', 'tripulacion'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use App\Models\Vuelo;
use App\Models\VueloEscala;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BuscadorController extends Controller
{
    public function index(Request $request)
    {
        $origin = trim((string) $request->query('origin', 'Bali'));
        $destination = trim((string) $request->query('destination', 'Bangkok'));
        $trip = $request->query('trip', 'round');
        $start = $request->query('start', '2026-11-13');
        $end = $request->query('end', '2026-11-20');
        $adults = max(1, (int) $request->query('adults', 3));
        $kids = max(0, (int) $request->query('kids', 0));
        $infants = max(0, (int) $request->query('infants', 0));
        $totalPax = $adults + $kids + $infants;

        // Catálogo de aeropuertos e IATAs de referencia
        $catalogoIata = [
            'bali' => ['iata' => 'DPS', 'aeropuerto' => 'Aeropuerto Internacional Ngurah Rai', 'pais' => 'Indonesia', 'nombre' => 'Bali'],
            'bangkok' => ['iata' => 'BKK', 'aeropuerto' => 'Aeropuerto Internacional Suvarnabhumi', 'pais' => 'Tailandia', 'nombre' => 'Bangkok'],
            'fukuoka' => ['iata' => 'FUK', 'aeropuerto' => 'Aeropuerto de Fukuoka', 'pais' => 'Japón', 'nombre' => 'Fukuoka'],
            'lima' => ['iata' => 'LIM', 'aeropuerto' => 'Aeropuerto Internacional Jorge Chávez', 'pais' => 'Perú', 'nombre' => 'Lima'],
            'tokio' => ['iata' => 'HND', 'aeropuerto' => 'Aeropuerto Internacional de Tokio - Haneda', 'pais' => 'Japón', 'nombre' => 'Tokio'],
            'cusco' => ['iata' => 'CUZ', 'aeropuerto' => 'Aeropuerto Internacional Alejandro Velasco Astete', 'pais' => 'Perú', 'nombre' => 'Cusco'],
            'arequipa' => ['iata' => 'AQP', 'aeropuerto' => 'Aeropuerto Alfredo Rodríguez Ballón', 'pais' => 'Perú', 'nombre' => 'Arequipa'],
            'cancún' => ['iata' => 'CUN', 'aeropuerto' => 'Aeropuerto Internacional de Cancún', 'pais' => 'México', 'nombre' => 'Cancún'],
            'cancun' => ['iata' => 'CUN', 'aeropuerto' => 'Aeropuerto Internacional de Cancún', 'pais' => 'México', 'nombre' => 'Cancún'],
            'madrid' => ['iata' => 'MAD', 'aeropuerto' => 'Aeropuerto Adolfo Suárez Madrid-Barajas', 'pais' => 'España', 'nombre' => 'Madrid'],
            'parís' => ['iata' => 'CDG', 'aeropuerto' => 'Aeropuerto Charles de Gaulle', 'pais' => 'Francia', 'nombre' => 'París'],
            'paris' => ['iata' => 'CDG', 'aeropuerto' => 'Aeropuerto Charles de Gaulle', 'pais' => 'Francia', 'nombre' => 'París'],
            'roma' => ['iata' => 'FCO', 'aeropuerto' => 'Aeropuerto de Roma-Fiumicino', 'pais' => 'Italia', 'nombre' => 'Roma'],
            'buenos aires' => ['iata' => 'EZE', 'aeropuerto' => 'Aeropuerto Internacional Ezeiza', 'pais' => 'Argentina', 'nombre' => 'Buenos Aires'],
        ];

        // Obtener destinos de la base de datos si existe la tabla
        $destinos = collect();
        $origenDestino = null;
        $destinoDestino = null;

        try {
            if (Schema::hasTable('destinos')) {
                $destinos = Destino::select('id', 'nombre', 'codigo_iata', 'aeropuerto', 'pais')
                    ->orderBy('nombre')
                    ->get();

                if (!empty($origin) && strtolower($origin) !== 'origen') {
                    $origenDestino = Destino::where('nombre', 'like', "%{$origin}%")
                        ->orWhere('codigo_iata', 'like', "%{$origin}%")
                        ->first();
                }

                if (!empty($destination) && strtolower($destination) !== 'destino') {
                    $destinoDestino = Destino::where('nombre', 'like', "%{$destination}%")
                        ->orWhere('codigo_iata', 'like', "%{$destination}%")
                        ->first();
                }
            }
        } catch (\Throwable $e) {}

        // Resolver IATA y nombres formales de origen y destino
        $keyOrig = strtolower($origin);
        $keyDest = strtolower($destination);

        $origenNom = $origenDestino?->nombre ?? ($catalogoIata[$keyOrig]['nombre'] ?? ucfirst($origin));
        $origenIata = $origenDestino?->codigo_iata ?? ($catalogoIata[$keyOrig]['iata'] ?? strtoupper(substr($origin, 0, 3)));
        $origenAero = $origenDestino?->aeropuerto ?? ($catalogoIata[$keyOrig]['aeropuerto'] ?? "Aeropuerto de {$origenNom}");

        $destinoNom = $destinoDestino?->nombre ?? ($catalogoIata[$keyDest]['nombre'] ?? ucfirst($destination));
        $destinoIata = $destinoDestino?->codigo_iata ?? ($catalogoIata[$keyDest]['iata'] ?? strtoupper(substr($destination, 0, 3)));
        $destinoAero = $destinoDestino?->aeropuerto ?? ($catalogoIata[$keyDest]['aeropuerto'] ?? "Aeropuerto de {$destinoNom}");

        // Intentar consultar vuelos existentes en MySQL
        $vuelosBd = collect();
        try {
            if (Schema::hasTable('vuelos')) {
                $q = Vuelo::with(['origen', 'destino', 'escalas.origen', 'escalas.llegada'])->where('activo', true);

                if ($origenDestino) {
                    $q->where('origen_id', $origenDestino->id);
                }
                if ($destinoDestino) {
                    $q->where('destino_id', $destinoDestino->id);
                }

                $vuelosBd = $q->orderBy('precio_final', 'asc')->get();
            }
        } catch (\Throwable $e) {}

        // Si existen vuelos reales en la BD para esa ruta, usarlos
        if ($vuelosBd->isNotEmpty()) {
            $vuelosData = $vuelosBd->map(function ($v) {
                return [
                    'id' => $v->id,
                    'codigo_vuelo' => $v->codigo_vuelo,
                    'aerolinea' => $v->aerolinea,
                    'origen' => [
                        'nombre' => $v->origen->nombre ?? '',
                        'codigo_iata' => $v->origen->codigo_iata ?? '',
                        'aeropuerto' => $v->origen->aeropuerto ?? '',
                    ],
                    'destino' => [
                        'nombre' => $v->destino->nombre ?? '',
                        'codigo_iata' => $v->destino->codigo_iata ?? '',
                        'aeropuerto' => $v->destino->aeropuerto ?? '',
                    ],
                    'fecha_salida' => $v->fecha_salida?->format('Y-m-d H:i'),
                    'fecha_llegada' => $v->fecha_llegada?->format('Y-m-d H:i'),
                    'duracion_minutos' => $v->duracion_minutos,
                    'duracion_formateada' => $v->duracion_formateada,
                    'dias_diferencia' => $v->dias_diferencia,
                    'cantidad_escalas' => $v->cantidad_escalas,
                    'incluye_articulo_personal' => (bool) $v->incluye_articulo_personal,
                    'incluye_equipaje_mano' => (bool) $v->incluye_equipaje_mano,
                    'incluye_equipaje_bodega' => (bool) $v->incluye_equipaje_bodega,
                    'moneda' => $v->moneda,
                    'precio_adulto' => (float) $v->precio_adulto,
                    'precio_menor' => (float) $v->precio_menor,
                    'precio_infante' => (float) $v->precio_infante,
                    'impuestos_tasas' => (float) $v->impuestos_tasas,
                    'precio_final' => (float) $v->precio_final,
                    'asientos_disponibles' => $v->asientos_disponibles,
                    'etiqueta' => $v->etiqueta,
                    'escalas' => $v->escalas->map(function ($e) {
                        return [
                            'orden' => $e->orden,
                            'numero_vuelo' => $e->numero_vuelo,
                            'aerolinea_operadora' => $e->aerolinea_operadora,
                            'origen' => $e->origen->nombre ?? '',
                            'origen_iata' => $e->origen->codigo_iata ?? '',
                            'llegada' => $e->llegada->nombre ?? '',
                            'llegada_iata' => $e->llegada->codigo_iata ?? '',
                            'hora_salida' => $e->hora_salida?->format('Y-m-d H:i'),
                            'hora_llegada' => $e->hora_llegada?->format('Y-m-d H:i'),
                            'duracion_tramo_minutos' => $e->duracion_tramo_minutos,
                            'tiempo_espera_minutos' => $e->tiempo_espera_minutos,
                            'cambio_avion' => (bool) $e->cambio_avion,
                        ];
                    }),
                ];
            });
        } else {
            // Generar resultados realistas dinámicos para la ruta buscada (ej. Bali -> Bangkok)
            $vuelosData = $this->generarVuelosDinamicos(
                $origenNom,
                $origenIata,
                $origenAero,
                $destinoNom,
                $destinoIata,
                $destinoAero,
                $start,
                $end
            );
        }

        // Formato para JavaScript (CITIES)
        $destinosData = $destinos->isNotEmpty()
            ? $destinos->map(fn($d) => [$d->nombre, $d->codigo_iata ?: strtoupper(substr($d->nombre, 0, 3)), $d->pais, $d->aeropuerto ?: $d->nombre])
            : collect();

        $searchParams = [
            'origin' => $origenNom,
            'destination' => $destinoNom,
            'originIata' => $origenIata,
            'destinationIata' => $destinoIata,
            'originName' => $origenNom,
            'destinationName' => $destinoNom,
            'trip' => $trip,
            'start' => $start,
            'end' => $end,
            'adults' => $adults,
            'kids' => $kids,
            'infants' => $infants,
            'totalPax' => $totalPax,
        ];

        if ($request->wantsJson()) {
            return response()->json([
                'vuelos' => $vuelosData,
                'destinos' => $destinosData,
                'params' => $searchParams,
            ]);
        }

        return view('search', compact(
            'destinos',
            'destinosData',
            'vuelosData',
            'searchParams',
            'origin',
            'destination',
            'trip',
            'start',
            'end',
            'adults',
            'kids',
            'infants',
            'totalPax'
        ));
    }

    /**
     * Genera opciones de vuelo dinámicas acordes al origen y destino consultados.
     */
    private function generarVuelosDinamicos($origNom, $origIata, $origAero, $destNom, $destIata, $destAero, $start, $end)
    {
        $fechaSalidaBase = Carbon::parse($start ?: '2026-11-13');

        $plantillas = [
            [
                'codigo' => 'TG-432',
                'aerolinea' => 'Thai Airways',
                'hora_salida' => '07:15',
                'duracion' => 265, // 4h 25m
                'escalas' => 0,
                'articulo' => true,
                'mano' => true,
                'bodega' => true,
                'precio' => 195.00,
                'tasas' => 55.00,
                'etiqueta' => 'el_mas_barato',
            ],
            [
                'codigo' => 'SQ-947',
                'aerolinea' => 'Singapore Airlines',
                'hora_salida' => '10:30',
                'duracion' => 375, // 6h 15m
                'escalas' => 1,
                'articulo' => true,
                'mano' => true,
                'bodega' => true,
                'precio' => 280.00,
                'tasas' => 75.00,
                'etiqueta' => 'mejor_opcion',
                'escala_ciudad' => 'Singapur',
                'escala_iata' => 'SIN',
            ],
            [
                'codigo' => 'PG-502',
                'aerolinea' => 'Bangkok Airways',
                'hora_salida' => '13:45',
                'duracion' => 250, // 4h 10m
                'escalas' => 0,
                'articulo' => true,
                'mano' => true,
                'bodega' => true,
                'precio' => 320.00,
                'tasas' => 85.00,
                'etiqueta' => 'menor_duracion',
            ],
            [
                'codigo' => 'MH-714',
                'aerolinea' => 'Malaysia Airlines',
                'hora_salida' => '16:00',
                'duracion' => 450, // 7h 30m
                'escalas' => 1,
                'articulo' => true,
                'mano' => true,
                'bodega' => false,
                'precio' => 220.00,
                'tasas' => 60.00,
                'etiqueta' => 'ninguna',
                'escala_ciudad' => 'Kuala Lumpur',
                'escala_iata' => 'KUL',
            ],
            [
                'codigo' => 'GA-850',
                'aerolinea' => 'Garuda Indonesia',
                'hora_salida' => '19:20',
                'duracion' => 395,
                'escalas' => 1,
                'articulo' => true,
                'mano' => true,
                'bodega' => true,
                'precio' => 265.00,
                'tasas' => 70.00,
                'etiqueta' => 'ninguna',
                'escala_ciudad' => 'Yakarta',
                'escala_iata' => 'CGK',
            ],
            [
                'codigo' => 'QR-843',
                'aerolinea' => 'Qatar Airways',
                'hora_salida' => '22:15',
                'duracion' => 510,
                'escalas' => 1,
                'articulo' => true,
                'mano' => true,
                'bodega' => true,
                'precio' => 410.00,
                'tasas' => 95.00,
                'etiqueta' => 'ninguna',
                'escala_ciudad' => 'Doha',
                'escala_iata' => 'DOH',
            ],
        ];

        return collect($plantillas)->map(function ($p, $idx) use ($origNom, $origIata, $origAero, $destNom, $destIata, $destAero, $fechaSalidaBase) {
            $h = explode(':', $p['hora_salida']);
            $salida = $fechaSalidaBase->copy()->setTime((int) $h[0], (int) $h[1], 0);
            $llegada = $salida->copy()->addMinutes($p['duracion']);
            $horas = intdiv($p['duracion'], 60);
            $minutos = $p['duracion'] % 60;
            $duracionFormateada = "{$horas}h " . str_pad((string) $minutos, 2, '0', STR_PAD_LEFT) . "m";

            $escalasList = [];
            if ($p['escalas'] > 0 && isset($p['escala_ciudad'])) {
                $t1 = (int) ($p['duracion'] * 0.45);
                $espera = 90;
                $t2 = $p['duracion'] - $t1;
                $escalasList = [
                    [
                        'orden' => 1,
                        'numero_vuelo' => $p['codigo'],
                        'aerolinea_operadora' => $p['aerolinea'],
                        'origen' => $origNom,
                        'origen_iata' => $origIata,
                        'llegada' => $p['escala_ciudad'],
                        'llegada_iata' => $p['escala_iata'],
                        'hora_salida' => $salida->format('Y-m-d H:i'),
                        'hora_llegada' => $salida->copy()->addMinutes($t1)->format('Y-m-d H:i'),
                        'duracion_tramo_minutos' => $t1,
                        'tiempo_espera_minutos' => $espera,
                        'cambio_avion' => true,
                    ],
                    [
                        'orden' => 2,
                        'numero_vuelo' => substr($p['codigo'], 0, 3) . rand(100, 999),
                        'aerolinea_operadora' => $p['aerolinea'],
                        'origen' => $p['escala_ciudad'],
                        'origen_iata' => $p['escala_iata'],
                        'llegada' => $destNom,
                        'llegada_iata' => $destIata,
                        'hora_salida' => $salida->copy()->addMinutes($t1 + $espera)->format('Y-m-d H:i'),
                        'hora_llegada' => $llegada->format('Y-m-d H:i'),
                        'duracion_tramo_minutos' => $t2,
                        'tiempo_espera_minutos' => null,
                        'cambio_avion' => false,
                    ],
                ];
            } else {
                $escalasList = [
                    [
                        'orden' => 1,
                        'numero_vuelo' => $p['codigo'],
                        'aerolinea_operadora' => $p['aerolinea'],
                        'origen' => $origNom,
                        'origen_iata' => $origIata,
                        'llegada' => $destNom,
                        'llegada_iata' => $destIata,
                        'hora_salida' => $salida->format('Y-m-d H:i'),
                        'hora_llegada' => $llegada->format('Y-m-d H:i'),
                        'duracion_tramo_minutos' => $p['duracion'],
                        'tiempo_espera_minutos' => null,
                        'cambio_avion' => false,
                    ]
                ];
            }

            return [
                'id' => $idx,
                'codigo_vuelo' => $p['codigo'],
                'aerolinea' => $p['aerolinea'],
                'origen' => [
                    'nombre' => $origNom,
                    'codigo_iata' => $origIata,
                    'aeropuerto' => $origAero,
                ],
                'destino' => [
                    'nombre' => $destNom,
                    'codigo_iata' => $destIata,
                    'aeropuerto' => $destAero,
                ],
                'fecha_salida' => $salida->format('Y-m-d H:i'),
                'fecha_llegada' => $llegada->format('Y-m-d H:i'),
                'duracion_minutos' => $p['duracion'],
                'duracion_formateada' => $duracionFormateada,
                'dias_diferencia' => $llegada->day !== $salida->day ? 1 : 0,
                'cantidad_escalas' => $p['escalas'],
                'incluye_articulo_personal' => $p['articulo'],
                'incluye_equipaje_mano' => $p['mano'],
                'incluye_equipaje_bodega' => $p['bodega'],
                'moneda' => 'USD',
                'precio_adulto' => (float) $p['precio'],
                'precio_menor' => (float) ($p['precio'] * 0.8),
                'precio_infante' => (float) ($p['precio'] * 0.1),
                'impuestos_tasas' => (float) $p['tasas'],
                'precio_final' => (float) ($p['precio'] + $p['tasas']),
                'asientos_disponibles' => 35,
                'etiqueta' => $p['etiqueta'],
                'escalas' => $escalasList,
            ];
        });
    }
}

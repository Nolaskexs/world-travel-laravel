<?php

namespace App\Http\Controllers;

use App\Models\Destino;
use App\Models\Vuelo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class BuscadorController extends Controller
{
    public function index(Request $request)
    {
        $originRaw = trim((string) $request->query('origin', ''));
        $destinationRaw = trim((string) $request->query('destination', ''));
        $trip = $request->query('trip', 'round');
        $start = $request->query('start', '2026-11-13');
        $end = $request->query('end', '2026-11-20');
        $adults = max(1, (int) $request->query('adults', 3));
        $kids = max(0, (int) $request->query('kids', 0));
        $infants = max(0, (int) $request->query('infants', 0));
        $totalPax = $adults + $kids + $infants;

        // Detectar si la búsqueda se realizó sin datos válidos
        $isOriginEmpty = empty($originRaw) || strcasecmp($originRaw, 'origen') === 0;
        $isDestEmpty = empty($destinationRaw) || strcasecmp($destinationRaw, 'destino') === 0;
        $busquedaIncompleta = $isOriginEmpty || $isDestEmpty;
        $avisoBusqueda = null;

        if ($busquedaIncompleta) {
            if ($isOriginEmpty && $isDestEmpty) {
                $avisoBusqueda = 'Por favor, ingresa una ciudad de origen y una ciudad de destino para realizar la búsqueda de vuelos.';
            } elseif ($isOriginEmpty) {
                $avisoBusqueda = 'Por favor, ingresa la ciudad de origen para encontrar los vuelos disponibles.';
            } else {
                $avisoBusqueda = 'Por favor, ingresa la ciudad de destino para encontrar los vuelos disponibles.';
            }
        }

        $origin = $isOriginEmpty ? '' : $originRaw;
        $destination = $isDestEmpty ? '' : $destinationRaw;

        // Catálogo de aeropuertos e IATAs de referencia
        $catalogoIata = [
            'bali' => ['iata' => 'DPS', 'aeropuerto' => 'Aeropuerto Internacional Ngurah Rai', 'pais' => 'Indonesia', 'nombre' => 'Bali'],
            'dps' => ['iata' => 'DPS', 'aeropuerto' => 'Aeropuerto Internacional Ngurah Rai', 'pais' => 'Indonesia', 'nombre' => 'Bali'],
            'bangkok' => ['iata' => 'BKK', 'aeropuerto' => 'Aeropuerto Internacional Suvarnabhumi', 'pais' => 'Tailandia', 'nombre' => 'Bangkok'],
            'bkk' => ['iata' => 'BKK', 'aeropuerto' => 'Aeropuerto Internacional Suvarnabhumi', 'pais' => 'Tailandia', 'nombre' => 'Bangkok'],
            'fukuoka' => ['iata' => 'FUK', 'aeropuerto' => 'Aeropuerto de Fukuoka', 'pais' => 'Japón', 'nombre' => 'Fukuoka'],
            'fuk' => ['iata' => 'FUK', 'aeropuerto' => 'Aeropuerto de Fukuoka', 'pais' => 'Japón', 'nombre' => 'Fukuoka'],
            'lima' => ['iata' => 'LIM', 'aeropuerto' => 'Aeropuerto Internacional Jorge Chávez', 'pais' => 'Perú', 'nombre' => 'Lima'],
            'lim' => ['iata' => 'LIM', 'aeropuerto' => 'Aeropuerto Internacional Jorge Chávez', 'pais' => 'Perú', 'nombre' => 'Lima'],
            'tokio' => ['iata' => 'HND', 'aeropuerto' => 'Aeropuerto Internacional de Tokio - Haneda', 'pais' => 'Japón', 'nombre' => 'Tokio'],
            'tokyo' => ['iata' => 'HND', 'aeropuerto' => 'Aeropuerto Internacional de Tokio - Haneda', 'pais' => 'Japón', 'nombre' => 'Tokio'],
            'hnd' => ['iata' => 'HND', 'aeropuerto' => 'Aeropuerto Internacional de Tokio - Haneda', 'pais' => 'Japón', 'nombre' => 'Tokio'],
            'nrt' => ['iata' => 'NRT', 'aeropuerto' => 'Aeropuerto Internacional de Tokio - Narita', 'pais' => 'Japón', 'nombre' => 'Tokio - Narita'],
            'tyo' => ['iata' => 'HND', 'aeropuerto' => 'Aeropuerto Internacional de Tokio - Haneda', 'pais' => 'Japón', 'nombre' => 'Tokio'],
            'cusco' => ['iata' => 'CUZ', 'aeropuerto' => 'Aeropuerto Internacional Alejandro Velasco Astete', 'pais' => 'Perú', 'nombre' => 'Cusco'],
            'cuz' => ['iata' => 'CUZ', 'aeropuerto' => 'Aeropuerto Internacional Alejandro Velasco Astete', 'pais' => 'Perú', 'nombre' => 'Cusco'],
            'arequipa' => ['iata' => 'AQP', 'aeropuerto' => 'Aeropuerto Alfredo Rodríguez Ballón', 'pais' => 'Perú', 'nombre' => 'Arequipa'],
            'aqp' => ['iata' => 'AQP', 'aeropuerto' => 'Aeropuerto Alfredo Rodríguez Ballón', 'pais' => 'Perú', 'nombre' => 'Arequipa'],
            'cancún' => ['iata' => 'CUN', 'aeropuerto' => 'Aeropuerto Internacional de Cancún', 'pais' => 'México', 'nombre' => 'Cancún'],
            'cancun' => ['iata' => 'CUN', 'aeropuerto' => 'Aeropuerto Internacional de Cancún', 'pais' => 'México', 'nombre' => 'Cancún'],
            'cun' => ['iata' => 'CUN', 'aeropuerto' => 'Aeropuerto Internacional de Cancún', 'pais' => 'México', 'nombre' => 'Cancún'],
            'madrid' => ['iata' => 'MAD', 'aeropuerto' => 'Aeropuerto Adolfo Suárez Madrid-Barajas', 'pais' => 'España', 'nombre' => 'Madrid'],
            'mad' => ['iata' => 'MAD', 'aeropuerto' => 'Aeropuerto Adolfo Suárez Madrid-Barajas', 'pais' => 'España', 'nombre' => 'Madrid'],
            'parís' => ['iata' => 'CDG', 'aeropuerto' => 'Aeropuerto Charles de Gaulle', 'pais' => 'Francia', 'nombre' => 'París'],
            'paris' => ['iata' => 'CDG', 'aeropuerto' => 'Aeropuerto Charles de Gaulle', 'pais' => 'Francia', 'nombre' => 'París'],
            'cdg' => ['iata' => 'CDG', 'aeropuerto' => 'Aeropuerto Charles de Gaulle', 'pais' => 'Francia', 'nombre' => 'París'],
            'roma' => ['iata' => 'FCO', 'aeropuerto' => 'Aeropuerto de Roma-Fiumicino', 'pais' => 'Italia', 'nombre' => 'Roma'],
            'fco' => ['iata' => 'FCO', 'aeropuerto' => 'Aeropuerto de Roma-Fiumicino', 'pais' => 'Italia', 'nombre' => 'Roma'],
            'buenos aires' => ['iata' => 'EZE', 'aeropuerto' => 'Aeropuerto Internacional Ezeiza', 'pais' => 'Argentina', 'nombre' => 'Buenos Aires'],
            'eze' => ['iata' => 'EZE', 'aeropuerto' => 'Aeropuerto Internacional Ezeiza', 'pais' => 'Argentina', 'nombre' => 'Buenos Aires'],
            'miami' => ['iata' => 'MIA', 'aeropuerto' => 'Aeropuerto Internacional de Miami', 'pais' => 'Estados Unidos', 'nombre' => 'Miami'],
            'mia' => ['iata' => 'MIA', 'aeropuerto' => 'Aeropuerto Internacional de Miami', 'pais' => 'Estados Unidos', 'nombre' => 'Miami'],
            'nueva york' => ['iata' => 'JFK', 'aeropuerto' => 'Aeropuerto Internacional John F. Kennedy', 'pais' => 'Estados Unidos', 'nombre' => 'Nueva York'],
            'jfk' => ['iata' => 'JFK', 'aeropuerto' => 'Aeropuerto Internacional John F. Kennedy', 'pais' => 'Estados Unidos', 'nombre' => 'Nueva York'],
            'santiago' => ['iata' => 'SCL', 'aeropuerto' => 'Aeropuerto Internacional Arturo Merino Benítez', 'pais' => 'Chile', 'nombre' => 'Santiago'],
            'scl' => ['iata' => 'SCL', 'aeropuerto' => 'Aeropuerto Internacional Arturo Merino Benítez', 'pais' => 'Chile', 'nombre' => 'Santiago'],
            'bogota' => ['iata' => 'BOG', 'aeropuerto' => 'Aeropuerto Internacional El Dorado', 'pais' => 'Colombia', 'nombre' => 'Bogotá'],
            'bogotá' => ['iata' => 'BOG', 'aeropuerto' => 'Aeropuerto Internacional El Dorado', 'pais' => 'Colombia', 'nombre' => 'Bogotá'],
            'bog' => ['iata' => 'BOG', 'aeropuerto' => 'Aeropuerto Internacional El Dorado', 'pais' => 'Colombia', 'nombre' => 'Bogotá'],
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

                if (! empty($origin) && strtolower($origin) !== 'origen') {
                    $origenDestino = Destino::where('nombre', 'like', "%{$origin}%")
                        ->orWhere('codigo_iata', 'like', "%{$origin}%")
                        ->first();
                }

                if (! empty($destination) && strtolower($destination) !== 'destino') {
                    $destinoDestino = Destino::where('nombre', 'like', "%{$destination}%")
                        ->orWhere('codigo_iata', 'like', "%{$destination}%")
                        ->first();
                }
            }
        } catch (\Throwable $e) {
        }

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
        } catch (\Throwable $e) {
        }

        // Si la búsqueda está incompleta (sin origen y/o sin destino), no generar vuelos ficticios
        if ($busquedaIncompleta) {
            $vuelosData = collect();
            $origenNom = $isOriginEmpty ? '' : $origenNom;
            $origenIata = $isOriginEmpty ? '' : $origenIata;
            $destinoNom = $isDestEmpty ? '' : $destinoNom;
            $destinoIata = $isDestEmpty ? '' : $destinoIata;
        } elseif ($vuelosBd->isNotEmpty()) {
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
                    'precio_menor' => (float) ($v->precio_menor ?? $v->precio_adulto * 0.8),
                    'precio_infante' => (float) ($v->precio_infante ?? $v->precio_adulto * 0.1),
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
            // Generar resultados realistas dinámicos para la ruta buscada (ej. Tokio -> Arequipa)
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
            ? $destinos->map(fn ($d) => [$d->nombre, $d->codigo_iata ?: strtoupper(substr($d->nombre, 0, 3)), $d->pais, $d->aeropuerto ?: $d->nombre])
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
            'busquedaIncompleta' => $busquedaIncompleta,
            'avisoBusqueda' => $avisoBusqueda,
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
            'totalPax',
            'busquedaIncompleta',
            'avisoBusqueda'
        ));
    }

    /**
     * Genera opciones de vuelo dinámicas acordes al origen y destino consultados.
     */
    private function generarVuelosDinamicos($origNom, $origIata, $origAero, $destNom, $destIata, $destAero, $start, $end)
    {
        $fechaSalidaBase = Carbon::parse($start ?: '2026-11-13');

        $continentes = [
            'HND' => 'asia', 'NRT' => 'asia', 'TYO' => 'asia', 'FUK' => 'asia', 'DPS' => 'asia', 'BKK' => 'asia', 'SIN' => 'asia', 'DXB' => 'asia',
            'LIM' => 'sudamerica', 'CUZ' => 'sudamerica', 'AQP' => 'sudamerica', 'TPP' => 'sudamerica', 'TYL' => 'sudamerica', 'JUL' => 'sudamerica', 'ICG' => 'sudamerica', 'EZE' => 'sudamerica', 'GIG' => 'sudamerica', 'SCL' => 'sudamerica', 'BOG' => 'sudamerica',
            'MAD' => 'europa', 'CDG' => 'europa', 'FCO' => 'europa', 'LHR' => 'europa', 'JTR' => 'europa',
            'CUN' => 'norteamerica', 'MEX' => 'norteamerica', 'MIA' => 'norteamerica', 'JFK' => 'norteamerica', 'LAX' => 'norteamerica',
        ];

        $cOrig = $continentes[$origIata] ?? 'otro';
        $cDest = $continentes[$destIata] ?? 'otro';

        // 1. Ruta Transpacífica / Asia <-> Sudamérica (ej. Tokio <-> Arequipa / Lima / Cusco)
        if (($cOrig === 'asia' && $cDest === 'sudamerica') || ($cOrig === 'sudamerica' && $cDest === 'asia')) {
            $plantillas = [
                [
                    'codigo' => 'UA-876',
                    'aerolinea' => 'United Airlines',
                    'hora_salida' => '07:15',
                    'duracion' => 1410, // 23h 30m
                    'escalas' => 1,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 750.00,
                    'tasas' => 165.00,
                    'etiqueta' => 'el_mas_barato',
                    'escala_ciudad' => 'Houston',
                    'escala_iata' => 'IAH',
                ],
                [
                    'codigo' => 'NH-176',
                    'aerolinea' => 'All Nippon Airways',
                    'hora_salida' => '10:30',
                    'duracion' => 1350, // 22h 30m
                    'escalas' => 1,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 890.00,
                    'tasas' => 195.00,
                    'etiqueta' => 'menor_duracion',
                    'escala_ciudad' => 'Los Ángeles',
                    'escala_iata' => 'LAX',
                ],
                [
                    'codigo' => 'LA-8012',
                    'aerolinea' => 'LATAM Airlines',
                    'hora_salida' => '13:45',
                    'duracion' => 1440, // 24h 00m
                    'escalas' => 1,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 820.00,
                    'tasas' => 180.00,
                    'etiqueta' => 'mejor_opcion',
                    'escala_ciudad' => ($destIata === 'LIM' || $origIata === 'LIM') ? 'Los Ángeles' : 'Lima',
                    'escala_iata' => ($destIata === 'LIM' || $origIata === 'LIM') ? 'LAX' : 'LIM',
                ],
                [
                    'codigo' => 'JL-062',
                    'aerolinea' => 'Japan Airlines',
                    'hora_salida' => '16:00',
                    'duracion' => 1470, // 24h 30m
                    'escalas' => 1,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 930.00,
                    'tasas' => 190.00,
                    'etiqueta' => 'ninguna',
                    'escala_ciudad' => 'Los Ángeles',
                    'escala_iata' => 'LAX',
                ],
                [
                    'codigo' => 'AA-168',
                    'aerolinea' => 'American Airlines',
                    'hora_salida' => '19:20',
                    'duracion' => 1530, // 25h 30m
                    'escalas' => 1,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => false,
                    'precio' => 780.00,
                    'tasas' => 170.00,
                    'etiqueta' => 'ninguna',
                    'escala_ciudad' => 'Dallas',
                    'escala_iata' => 'DFW',
                ],
                [
                    'codigo' => 'DL-294',
                    'aerolinea' => 'Delta Air Lines',
                    'hora_salida' => '22:15',
                    'duracion' => 1590, // 26h 30m
                    'escalas' => 1,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 860.00,
                    'tasas' => 185.00,
                    'etiqueta' => 'ninguna',
                    'escala_ciudad' => 'Atlanta',
                    'escala_iata' => 'ATL',
                ],
            ];
        }
        // 2. Rutas Nacionales / Domésticas en Sudamérica (ej. Lima <-> Arequipa / Cusco)
        elseif ($cOrig === 'sudamerica' && $cDest === 'sudamerica' && in_array($origIata, ['LIM', 'CUZ', 'AQP', 'TPP', 'TYL', 'JUL', 'ICG']) && in_array($destIata, ['LIM', 'CUZ', 'AQP', 'TPP', 'TYL', 'JUL', 'ICG'])) {
            $plantillas = [
                [
                    'codigo' => 'LA-2104',
                    'aerolinea' => 'LATAM Airlines',
                    'hora_salida' => '06:30',
                    'duracion' => 85, // 1h 25m
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 65.00,
                    'tasas' => 18.00,
                    'etiqueta' => 'el_mas_barato',
                ],
                [
                    'codigo' => 'H8-5012',
                    'aerolinea' => 'Sky Airline',
                    'hora_salida' => '09:15',
                    'duracion' => 80, // 1h 20m
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => false,
                    'precio' => 72.00,
                    'tasas' => 19.00,
                    'etiqueta' => 'menor_duracion',
                ],
                [
                    'codigo' => 'LA-2118',
                    'aerolinea' => 'LATAM Airlines',
                    'hora_salida' => '12:40',
                    'duracion' => 85,
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 85.00,
                    'tasas' => 20.00,
                    'etiqueta' => 'mejor_opcion',
                ],
                [
                    'codigo' => 'JA-3100',
                    'aerolinea' => 'JetSMART',
                    'hora_salida' => '15:20',
                    'duracion' => 90,
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => false,
                    'precio' => 59.00,
                    'tasas' => 16.00,
                    'etiqueta' => 'ninguna',
                ],
                [
                    'codigo' => 'LA-2130',
                    'aerolinea' => 'LATAM Airlines',
                    'hora_salida' => '18:05',
                    'duracion' => 85,
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 95.00,
                    'tasas' => 22.00,
                    'etiqueta' => 'ninguna',
                ],
                [
                    'codigo' => 'H8-5034',
                    'aerolinea' => 'Sky Airline',
                    'hora_salida' => '21:10',
                    'duracion' => 80,
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 88.00,
                    'tasas' => 20.00,
                    'etiqueta' => 'ninguna',
                ],
            ];
        }
        // 3. Rutas Europa <-> Sudamérica (ej. Madrid / París / Roma <-> Lima / Arequipa)
        elseif (($cOrig === 'europa' && $cDest === 'sudamerica') || ($cOrig === 'sudamerica' && $cDest === 'europa')) {
            $plantillas = [
                [
                    'codigo' => 'IB-6651',
                    'aerolinea' => 'Iberia',
                    'hora_salida' => '11:45',
                    'duracion' => 745, // 12h 25m
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 590.00,
                    'tasas' => 140.00,
                    'etiqueta' => 'el_mas_barato',
                ],
                [
                    'codigo' => 'UX-075',
                    'aerolinea' => 'Air Europa',
                    'hora_salida' => '14:20',
                    'duracion' => 730, // 12h 10m
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 640.00,
                    'tasas' => 150.00,
                    'etiqueta' => 'menor_duracion',
                ],
                [
                    'codigo' => 'LA-705',
                    'aerolinea' => 'LATAM Airlines',
                    'hora_salida' => '17:10',
                    'duracion' => 755,
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 620.00,
                    'tasas' => 145.00,
                    'etiqueta' => 'mejor_opcion',
                ],
                [
                    'codigo' => 'AF-480',
                    'aerolinea' => 'Air France',
                    'hora_salida' => '10:00',
                    'duracion' => 870,
                    'escalas' => 1,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 690.00,
                    'tasas' => 160.00,
                    'etiqueta' => 'ninguna',
                    'escala_ciudad' => 'París',
                    'escala_iata' => 'CDG',
                ],
                [
                    'codigo' => 'KL-743',
                    'aerolinea' => 'KLM',
                    'hora_salida' => '13:15',
                    'duracion' => 890,
                    'escalas' => 1,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 710.00,
                    'tasas' => 165.00,
                    'etiqueta' => 'ninguna',
                    'escala_ciudad' => 'Ámsterdam',
                    'escala_iata' => 'AMS',
                ],
                [
                    'codigo' => 'IB-6659',
                    'aerolinea' => 'Iberia',
                    'hora_salida' => '23:30',
                    'duracion' => 740,
                    'escalas' => 0,
                    'articulo' => true,
                    'mano' => true,
                    'bodega' => true,
                    'precio' => 680.00,
                    'tasas' => 145.00,
                    'etiqueta' => 'ninguna',
                ],
            ];
        }
        // 4. Default / Sudeste Asiático (ej. Bali <-> Bangkok) y otras
        else {
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
        }

        return collect($plantillas)->map(function ($p, $idx) use ($origNom, $origIata, $origAero, $destNom, $destIata, $destAero, $fechaSalidaBase) {
            $h = explode(':', $p['hora_salida']);
            $salida = $fechaSalidaBase->copy()->setTime((int) $h[0], (int) $h[1], 0);
            $llegada = $salida->copy()->addMinutes($p['duracion']);
            $horas = intdiv($p['duracion'], 60);
            $minutos = $p['duracion'] % 60;
            $duracionFormateada = "{$horas}h ".str_pad((string) $minutos, 2, '0', STR_PAD_LEFT).'m';

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
                        'numero_vuelo' => substr($p['codigo'], 0, 3).rand(100, 999),
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
                    ],
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

<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Si es admin, lo mandamos a su panel de administración /admin
        if ($user && $user->esAdmin()) {
            return redirect('/admin');
        }

        // 2. Información del usuario obtenida dinámicamente desde la BD
        $nombreCompleto = trim($user->name);
        $partes = preg_split('/\s+/', $nombreCompleto) ?: [$nombreCompleto];
        $primerNombre = $partes[0] ?? $nombreCompleto;
        $iniciales = '';
        if (count($partes) >= 2) {
            $iniciales = mb_strtoupper(mb_substr($partes[0], 0, 1).mb_substr($partes[1], 0, 1));
        } else {
            $iniciales = mb_strtoupper(mb_substr($primerNombre, 0, 2));
        }

        $usuario = [
            'nombre' => $nombreCompleto,
            'primer_nombre' => $primerNombre,
            'iniciales' => $iniciales ?: 'WT',
            'avatar' => 'img/avatars/jair.jpg',
            'progreso' => $user->email_verified_at ? 100 : 85,
        ];

        // 3. Consultar las reservas del cliente desde la base de datos con relaciones
        $reservas = Reserva::with(['paquete', 'alojamiento', 'vuelo', 'oferta', 'pasajeros'])
            ->where('user_id', $user->id)
            ->orderBy('fecha_viaje')
            ->get();

        // 4. Métricas calculadas dinámicamente desde la BD
        $vuelosActivos = $reservas->where('estado', 'confirmada')
            ->filter(fn ($r) => $r->fecha_viaje && $r->fecha_viaje >= now()->startOfDay())
            ->count();

        // Check-in disponible si faltan 7 días o menos para el viaje y está confirmada
        $checkInDisponible = $reservas->where('estado', 'confirmada')
            ->filter(function ($r) {
                return $r->fecha_viaje
                    && $r->fecha_viaje >= now()->startOfDay()
                    && $r->fecha_viaje <= now()->addDays(7)->endOfDay();
            })
            ->count();

        $reservasPendientes = $reservas->where('estado', 'pendiente')->count();

        $metricas = [
            ['label' => 'Vuelos Activos',      'valor' => $vuelosActivos,      'icono' => 'plane',    'tono' => 'blue'],
            ['label' => 'Check-in Disponible', 'valor' => $checkInDisponible, 'icono' => 'check',    'tono' => 'green'],
            ['label' => 'Reservas Pendientes', 'valor' => $reservasPendientes, 'icono' => 'calendar', 'tono' => 'purple'],
        ];

        // 5. Viajes del usuario
        $viajes = $reservas->map(function ($reserva) {
            $paquete = $reserva->paquete;
            $estado = $reserva->estado;

            $estadoLabel = match ($estado) {
                'confirmada' => 'Confirmado',
                'pendiente' => 'Pendiente de pago',
                'cancelada' => 'Cancelada',
                default => ucfirst($estado),
            };

            // Si está dentro de los 3 días de viaje, mostrar check-in disponible
            if ($estado === 'confirmada' && $reserva->fecha_viaje && $reserva->fecha_viaje <= now()->addDays(3) && $reserva->fecha_viaje >= now()->startOfDay()) {
                $estado = 'checkin';
                $estadoLabel = 'Check-in Disponible';
            }

            $titulo = $reserva->detalle_servicio
                ?: ($paquete ? ($paquete->titulo ?? "Viaje a {$paquete->destino}")
                : ($reserva->alojamiento ? $reserva->alojamiento->nombre
                : ($reserva->vuelo ? "Vuelo {$reserva->vuelo->numero_vuelo}"
                : ($reserva->oferta ? $reserva->oferta->titulo : "Reserva #{$reserva->id}"))));

            $img = $paquete?->imagen_url
                ?: ($reserva->alojamiento ? $reserva->alojamiento->imagen_url
                : 'icons/roma.jpg');

            if (! str_starts_with($img, 'http') && ! str_starts_with($img, 'img/') && ! str_starts_with($img, 'icons/')) {
                $img = 'icons/'.$img;
            }

            $asientosTodos = $reserva->pasajeros->pluck('asiento')->filter()->values()->all();
            $asientosTexto = ! empty($asientosTodos) ? implode(', ', $asientosTodos) : ($reserva->asientos ?? null);

            return [
                'id' => $reserva->id,
                'titulo' => $titulo,
                'fecha' => $reserva->fecha_viaje ? $reserva->fecha_viaje->translatedFormat('d M Y') : ($reserva->fecha_entrada ? $reserva->fecha_entrada->translatedFormat('d M Y') : 'Fecha pendiente'),
                'estado' => $estado,
                'estado_label' => $estadoLabel,
                'destacado' => 'US$ '.number_format($reserva->total, 2),
                'total_num' => (float) $reserva->total,
                'nota' => $reserva->num_pasajeros.' '.($reserva->num_pasajeros === 1 ? 'pasajero' : 'pasajeros'),
                'asientos_texto' => $asientosTexto,
                'imagen' => $img,
                'checkin_url' => route('asientos.checkin', $reserva->id),
            ];
        })->values()->all();

        // 6. Historial de tramos / vuelos a partir de las reservas y sus pasajeros
        $historial = [];
        foreach ($reservas as $reserva) {
            if ($reserva->tipo_servicio === 'alojamiento' || (! empty($reserva->alojamiento_id) && empty($reserva->vuelo_id) && empty($reserva->paquete_id))) {
                continue;
            }

            $paquete = $reserva->paquete;
            $vuelo = $reserva->vuelo;
            $oferta = $reserva->oferta;

            $esNacional = ($paquete && ($paquete->pais === 'Perú' || strtolower($paquete->continente ?? '') === 'nacional'))
                || ($vuelo && $vuelo->origen?->pais === 'Perú' && $vuelo->destino?->pais === 'Perú');
            $tipo = $esNacional ? 'Nacional' : 'Internacional';

            $destinoCiudad = $paquete?->destino ?: ($vuelo?->destino?->nombre ?: ($oferta?->paquete?->destino ?: 'Destino'));
            $destinoCode = $vuelo?->destino?->codigo_iata ?: strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $destinoCiudad) ?: 'DES', 0, 3));
            $origenCiudad = $vuelo?->origen?->nombre ?: 'Lima';
            $origenCode = $vuelo?->origen?->codigo_iata ?: 'LIM';

            $pasajerosLista = $reserva->pasajeros;
            if ($pasajerosLista->isEmpty()) {
                $pasajerosLista = collect([(object) [
                    'nombre' => $user->name,
                    'asiento' => $reserva->asiento ?? null,
                ]]);
            }

            foreach ($pasajerosLista as $p) {
                $pPartes = preg_split('/\s+/', trim($p->nombre)) ?: [$p->nombre];
                $pInit = mb_strtoupper(mb_substr($pPartes[0] ?? 'V', 0, 1).mb_substr($pPartes[1] ?? '', 0, 1));

                $historial[] = [
                    'pasajero' => $p->nombre,
                    'iniciales' => $pInit ?: 'WT',
                    'avatar' => 'img/avatars/jair.jpg',
                    'fecha' => $reserva->fecha_viaje ? $reserva->fecha_viaje->format('d/m/Y') : now()->format('d/m/Y'),
                    'tipo' => $tipo,
                    'origen' => $origenCode,
                    'origen_ciudad' => $origenCiudad,
                    'destino' => $destinoCode,
                    'destino_ciudad' => $destinoCiudad,
                    'asiento' => $p->asiento ?? null,
                    'accion_label' => $reserva->estado === 'confirmada' ? 'Check-in / Asientos' : 'Detalles',
                    'accion_url' => route('asientos.checkin', $reserva->id),
                ];
            }
        }

        // 7. Pasajeros frecuentes / favoritos basados en las reservas del cliente
        $nombresFrecuentes = $reservas->flatMap(fn ($r) => $r->pasajeros->pluck('nombre'))
            ->filter()
            ->unique()
            ->values();

        if ($nombresFrecuentes->isEmpty()) {
            $nombresFrecuentes = collect([$user->name]);
        }

        $frecuentes = $nombresFrecuentes->map(function ($nom) {
            $partesP = preg_split('/\s+/', trim($nom)) ?: [$nom];
            $init = mb_strtoupper(mb_substr($partesP[0] ?? 'V', 0, 1).mb_substr($partesP[1] ?? '', 0, 1));

            return [
                'nombre' => $nom,
                'iniciales' => $init ?: 'WT',
                'avatar' => 'img/avatars/jair.jpg',
                'estado' => 'Viajero registrado',
            ];
        })->take(5)->all();

        // 8. Cálculo de millas acumuladas
        $mesesNombres = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $mesActualIdx = (int) now()->format('n') - 1;

        $millas = [];
        for ($i = 4; $i >= 0; $i--) {
            $idx = ($mesActualIdx - $i + 12) % 12;
            $nombreMes = $mesesNombres[$idx];

            $millasNac = $reservas->where('estado', 'confirmada')
                ->filter(fn ($r) => $r->paquete?->pais === 'Perú')
                ->count() * 600 + 400;
            $millasInt = $reservas->where('estado', 'confirmada')
                ->filter(fn ($r) => $r->paquete?->pais !== 'Perú')
                ->count() * 800 + 500;

            $millas[] = [
                'mes' => $nombreMes,
                'nacional' => $millasNac + ($i * 150),
                'internacional' => $millasInt + ($i * 200),
            ];
        }

        // 9. Tripulación / Asistencia
        $tripulacion = [
            ['nombre' => 'Soporte 24/7',             'iniciales' => 'S',  'avatar' => 'img/avatars/piloto-1.jpg', 'rol' => 'Soporte',      'accion' => 'Contactar'],
            ['nombre' => 'Asistencia en Aeropuerto', 'iniciales' => 'AA', 'avatar' => 'img/avatars/piloto-2.jpg', 'rol' => 'Aeropuerto', 'accion' => 'Seguir'],
        ];

        // 10. Reservas organizadas por categorías del header: Vuelos, Alojamientos, Paquetes, Ofertas
        $categoriasReservas = [
            'vuelos' => $reservas->filter(fn ($r) => $r->tipo_servicio === 'vuelo' || (! empty($r->vuelo_id) && empty($r->paquete_id) && empty($r->oferta_id)))->values(),
            'alojamientos' => $reservas->filter(fn ($r) => $r->tipo_servicio === 'alojamiento' || (! empty($r->alojamiento_id) && empty($r->paquete_id)))->values(),
            'paquetes' => $reservas->filter(fn ($r) => $r->tipo_servicio === 'paquete' || (! empty($r->paquete_id) && empty($r->oferta_id)))->values(),
            'ofertas' => $reservas->filter(fn ($r) => $r->tipo_servicio === 'oferta' || ! empty($r->oferta_id))->values(),
        ];

        return view('dashboard', compact(
            'usuario', 'metricas', 'viajes', 'historial', 'frecuentes', 'millas', 'tripulacion', 'categoriasReservas'
        ));
    }
}

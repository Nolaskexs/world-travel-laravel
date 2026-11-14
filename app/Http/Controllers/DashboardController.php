<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Si es admin, lo mandamos a su panel exclusivo.
        if ($user->esAdmin()) {
            return redirect('/admin');
        }

        // 2. Información del usuario obtenida dinámicamente desde la BD
        $nombreCompleto = trim($user->name);
        $partes = preg_split('/\s+/', $nombreCompleto) ?: [$nombreCompleto];
        $primerNombre = $partes[0] ?? $nombreCompleto;
        $iniciales = '';
        if (count($partes) >= 2) {
            $iniciales = mb_strtoupper(mb_substr($partes[0], 0, 1) . mb_substr($partes[1], 0, 1));
        } else {
            $iniciales = mb_strtoupper(mb_substr($primerNombre, 0, 2));
        }

        $usuario = [
            'nombre'        => $nombreCompleto,
            'primer_nombre' => $primerNombre,
            'iniciales'     => $iniciales ?: 'WT',
            'avatar'        => 'img/avatars/jair.jpg',
            'progreso'      => $user->email_verified_at ? 100 : 85,
        ];

        // 3. Consultar las reservas del cliente desde la base de datos con relaciones
        $reservas = Reserva::with(['paquete', 'pasajeros'])
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
                'pendiente'  => 'Pendiente de pago',
                'cancelada'  => 'Cancelada',
                default      => ucfirst($estado),
            };

            // Si está dentro de los 3 días de viaje, mostrar check-in disponible
            if ($estado === 'confirmada' && $reserva->fecha_viaje && $reserva->fecha_viaje <= now()->addDays(3) && $reserva->fecha_viaje >= now()->startOfDay()) {
                $estado = 'checkin';
                $estadoLabel = 'Check-in Disponible';
            }

            $img = $paquete?->imagen_url ?: 'img/viajes/roma.jpg';
            if (!str_starts_with($img, 'http') && !str_starts_with($img, 'img/')) {
                $img = 'img/viajes/' . $img;
            }

            return [
                'titulo'       => $paquete ? ($paquete->titulo ?? "Viaje a {$paquete->destino}") : "Reserva #{$reserva->id}",
                'fecha'        => $reserva->fecha_viaje ? $reserva->fecha_viaje->translatedFormat('d M Y') : 'Fecha pendiente',
                'estado'       => $estado,
                'estado_label' => $estadoLabel,
                'destacado'    => 'US$ ' . number_format($reserva->total, 2),
                'nota'         => $reserva->num_pasajeros . ' ' . ($reserva->num_pasajeros === 1 ? 'pasajero' : 'pasajeros'),
                'imagen'       => $img,
            ];
        })->values()->all();

        // 6. Historial de tramos / vuelos a partir de las reservas y sus pasajeros
        $historial = [];
        foreach ($reservas as $reserva) {
            $paquete = $reserva->paquete;
            $esNacional = $paquete && ($paquete->pais === 'Perú' || strtolower($paquete->continente ?? '') === 'nacional');
            $tipo = $esNacional ? 'Nacional' : 'Internacional';
            $destinoCiudad = $paquete ? $paquete->destino : 'Destino';
            $destinoCode = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $destinoCiudad) ?: 'VIA', 0, 3));

            $pasajerosLista = $reserva->pasajeros;
            if ($pasajerosLista->isEmpty()) {
                $pasajerosLista = collect([(object)[
                    'nombre' => $user->name,
                ]]);
            }

            foreach ($pasajerosLista as $p) {
                $pPartes = preg_split('/\s+/', trim($p->nombre)) ?: [$p->nombre];
                $pInit = mb_strtoupper(mb_substr($pPartes[0] ?? 'V', 0, 1) . mb_substr($pPartes[1] ?? '', 0, 1));

                $historial[] = [
                    'pasajero'       => $p->nombre,
                    'iniciales'      => $pInit ?: 'WT',
                    'avatar'         => 'img/avatars/jair.jpg',
                    'fecha'          => $reserva->fecha_viaje ? $reserva->fecha_viaje->format('d/m/Y') : now()->format('d/m/Y'),
                    'tipo'           => $tipo,
                    'origen'         => 'LIM',
                    'origen_ciudad'  => 'Lima',
                    'destino'        => $destinoCode,
                    'destino_ciudad' => $destinoCiudad,
                    'accion_label'   => $reserva->estado === 'confirmada' ? 'Ver boleto' : 'Detalles',
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
            $init = mb_strtoupper(mb_substr($partesP[0] ?? 'V', 0, 1) . mb_substr($partesP[1] ?? '', 0, 1));
            return [
                'nombre'    => $nom,
                'iniciales' => $init ?: 'WT',
                'avatar'    => 'img/avatars/jair.jpg',
                'estado'    => 'Viajero registrado',
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
                'mes'           => $nombreMes,
                'nacional'      => $millasNac + ($i * 150),
                'internacional' => $millasInt + ($i * 200),
            ];
        }

        // 9. Tripulación / Asistencia
        $tripulacion = [
            ['nombre' => 'Soporte 24/7',             'iniciales' => 'S',  'avatar' => 'img/avatars/piloto-1.jpg', 'rol' => 'Soporte',      'accion' => 'Contactar'],
            ['nombre' => 'Asistencia en Aeropuerto', 'iniciales' => 'AA', 'avatar' => 'img/avatars/piloto-2.jpg', 'rol' => 'Aeropuerto', 'accion' => 'Seguir'],
        ];

        return view('dashboard', compact(
            'usuario', 'metricas', 'viajes', 'historial', 'frecuentes', 'millas', 'tripulacion'
        ));
    }
}

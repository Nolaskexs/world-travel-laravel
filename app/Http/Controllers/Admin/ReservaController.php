<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservaController extends Controller
{
    /**
     * Listar, buscar y filtrar las reservas.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $estado = (string) $request->query('estado', '');

        $estadosPermitidos = [
            'pendiente',
            'confirmada',
            'cancelada',
        ];

        $consulta = DB::table('reservas')
            ->leftJoin(
                'users',
                'users.id',
                '=',
                'reservas.user_id'
            )
            ->leftJoin(
                'paquetes',
                'paquetes.id',
                '=',
                'reservas.paquete_id'
            )
            ->leftJoin(
                'alojamientos',
                'alojamientos.id',
                '=',
                'reservas.alojamiento_id'
            )
            ->leftJoin(
                'vuelos',
                'vuelos.id',
                '=',
                'reservas.vuelo_id'
            )
            ->leftJoin(
                'ofertas',
                'ofertas.id',
                '=',
                'reservas.oferta_id'
            )
            ->select(
                'reservas.*',
                DB::raw('COALESCE(users.name, "Cliente Registrado") as cliente_nombre'),
                DB::raw('COALESCE(users.email, "cliente@worldtravel.com") as cliente_email'),
                DB::raw('COALESCE(paquetes.titulo, alojamientos.nombre, vuelos.codigo_vuelo, ofertas.titulo, "Viaje Turístico / Vuelo") as paquete_titulo')
            );

        // Buscar por cliente, correo, paquete, hotel, vuelo o ID.
        if ($busqueda !== '') {
            $consulta->where(function ($query) use ($busqueda) {
                $query
                    ->where('users.name', 'like', "%{$busqueda}%")
                    ->orWhere('users.email', 'like', "%{$busqueda}%")
                    ->orWhere('paquetes.titulo', 'like', "%{$busqueda}%")
                    ->orWhere('alojamientos.nombre', 'like', "%{$busqueda}%")
                    ->orWhere('ofertas.titulo', 'like', "%{$busqueda}%")
                    ->orWhere('vuelos.codigo_vuelo', 'like', "%{$busqueda}%");

                if (ctype_digit($busqueda)) {
                    $query->orWhere(
                        'reservas.id',
                        '=',
                        (int) $busqueda
                    );
                }
            });
        }

        // Filtrar por estado.
        if (in_array($estado, $estadosPermitidos, true)) {
            $consulta->where('reservas.estado', $estado);
        }

        $reservas = $consulta
            ->orderByDesc('reservas.id')
            ->paginate(10)
            ->withQueryString();

        // Obtener asientos agrupados para las reservas listadas
        $reservaIds = collect($reservas->items())->pluck('id')->all();
        $pasajerosPorReserva = DB::table('reserva_pasajeros')
            ->whereIn('reserva_id', $reservaIds)
            ->whereNotNull('asiento')
            ->where('asiento', '!=', '')
            ->get()
            ->groupBy('reserva_id');

        foreach ($reservas as $r) {
            $asientosLista = isset($pasajerosPorReserva[$r->id])
                ? $pasajerosPorReserva[$r->id]->pluck('asiento')->all()
                : [];
            $r->asientos_texto = ! empty($asientosLista) ? implode(', ', $asientosLista) : null;
        }

        // Estadísticas reales desde MySQL.
        $totalReservas = DB::table('reservas')->count();

        $pendientes = DB::table('reservas')
            ->where('estado', 'pendiente')
            ->count();

        $confirmadas = DB::table('reservas')
            ->where('estado', 'confirmada')
            ->count();

        $canceladas = DB::table('reservas')
            ->where('estado', 'cancelada')
            ->count();

        return view('admin.reservas.index', compact(
            'reservas',
            'busqueda',
            'estado',
            'totalReservas',
            'pendientes',
            'confirmadas',
            'canceladas'
        ));
    }

    /**
     * Mostrar los detalles de una reserva.
     */
    public function show(int $reserva): View
    {
        $reservaData = DB::table('reservas')
            ->leftJoin(
                'users',
                'users.id',
                '=',
                'reservas.user_id'
            )
            ->leftJoin(
                'paquetes',
                'paquetes.id',
                '=',
                'reservas.paquete_id'
            )
            ->leftJoin(
                'alojamientos',
                'alojamientos.id',
                '=',
                'reservas.alojamiento_id'
            )
            ->leftJoin(
                'vuelos',
                'vuelos.id',
                '=',
                'reservas.vuelo_id'
            )
            ->leftJoin(
                'ofertas',
                'ofertas.id',
                '=',
                'reservas.oferta_id'
            )
            ->select(
                'reservas.*',
                DB::raw('COALESCE(users.name, "Cliente Registrado") as cliente_nombre'),
                DB::raw('COALESCE(users.email, "cliente@worldtravel.com") as cliente_email'),
                DB::raw('COALESCE(paquetes.titulo, alojamientos.nombre, vuelos.codigo_vuelo, ofertas.titulo, "Viaje Turístico / Vuelo") as paquete_titulo'),
                DB::raw('COALESCE(paquetes.destino, alojamientos.ubicacion, ofertas.titulo, "Destino") as paquete_destino'),
                DB::raw('COALESCE(paquetes.pais, "Mundo") as paquete_pais')
            )
            ->where('reservas.id', $reserva)
            ->first();

        abort_if(! $reservaData, 404);

        $pasajeros = DB::table('reserva_pasajeros')
            ->where('reserva_id', $reserva)
            ->orderBy('id')
            ->get();

        return view('admin.reservas.show', [
            'reserva' => $reservaData,
            'pasajeros' => $pasajeros,
        ]);
    }

    /**
     * Actualizar el estado de una reserva.
     */
    public function update(
        Request $request,
        int $reserva
    ): RedirectResponse {
        $datos = $request->validate([
            'estado' => [
                'required',
                Rule::in([
                    'pendiente',
                    'confirmada',
                    'cancelada',
                ]),
            ],
        ]);

        $existe = DB::table('reservas')
            ->where('id', $reserva)
            ->exists();

        abort_unless($existe, 404);

        DB::table('reservas')
            ->where('id', $reserva)
            ->update([
                'estado' => $datos['estado'],
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('admin.reservas.show', $reserva)
            ->with(
                'success',
                'Estado de la reserva actualizado correctamente.'
            );
    }
}

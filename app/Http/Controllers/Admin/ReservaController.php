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
            ->join(
                'users',
                'users.id',
                '=',
                'reservas.user_id'
            )
            ->join(
                'paquetes',
                'paquetes.id',
                '=',
                'reservas.paquete_id'
            )
            ->select(
                'reservas.*',
                'users.name as cliente_nombre',
                'users.email as cliente_email',
                'paquetes.titulo as paquete_titulo'
            );

        // Buscar por cliente, correo, paquete o ID.
        if ($busqueda !== '') {
            $consulta->where(function ($query) use ($busqueda) {
                $query
                    ->where('users.name', 'like', "%{$busqueda}%")
                    ->orWhere('users.email', 'like', "%{$busqueda}%")
                    ->orWhere('paquetes.titulo', 'like', "%{$busqueda}%");

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
            ->join(
                'users',
                'users.id',
                '=',
                'reservas.user_id'
            )
            ->join(
                'paquetes',
                'paquetes.id',
                '=',
                'reservas.paquete_id'
            )
            ->select(
                'reservas.*',
                'users.name as cliente_nombre',
                'users.email as cliente_email',
                'paquetes.titulo as paquete_titulo',
                'paquetes.destino as paquete_destino',
                'paquetes.pais as paquete_pais'
            )
            ->where('reservas.id', $reserva)
            ->first();

        abort_if(!$reservaData, 404);

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

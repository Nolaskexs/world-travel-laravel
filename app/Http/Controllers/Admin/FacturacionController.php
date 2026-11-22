<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FacturacionController extends Controller
{
    /**
     * Listar las operaciones de pago registradas.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $estado = (string) $request->query('estado', '');

        $consulta = DB::table('pagos')
            ->join(
                'reservas',
                'reservas.id',
                '=',
                'pagos.reserva_id'
            )
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
                'pagos.*',
                'reservas.estado as reserva_estado',
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
                    $query
                        ->orWhere('pagos.id', (int) $busqueda)
                        ->orWhere('reservas.id', (int) $busqueda);
                }
            });
        }

        // Filtrar por el estado del pago.
        if (in_array(
            $estado,
            ['aprobado', 'rechazado', 'pendiente', 'procesando'],
            true
        )) {
            $consulta->where('pagos.estado', $estado);
        }

        $operaciones = $consulta
            ->orderByDesc('pagos.id')
            ->paginate(10)
            ->withQueryString();

        // Estadísticas de los pagos.
        $pagosAprobados = DB::table('pagos')
            ->where('estado', 'aprobado')
            ->count();

        $pagosRechazados = DB::table('pagos')
            ->where('estado', 'rechazado')
            ->count();

        $reservasPendientes = DB::table('reservas')
            ->where('estado', 'pendiente')
            ->count();

        $totalOperaciones = DB::table('pagos')->count();

        // Separar importes por moneda.
        // Solo operaciones del simulador académico.
        $totalesSimulados = DB::table('pagos')
            ->select('moneda')
            ->selectRaw('SUM(monto) as total')
            ->where('proveedor', 'simulador')
            ->where('estado', 'aprobado')
            ->groupBy('moneda')
            ->orderBy('moneda')
            ->get();

        return view('admin.facturacion.index', compact(
            'operaciones',
            'busqueda',
            'estado',
            'pagosAprobados',
            'pagosRechazados',
            'reservasPendientes',
            'totalOperaciones',
            'totalesSimulados'
        ));
    }

    /**
     * Mostrar al administrador un comprobante de prueba.
     */
    public function comprobante(Pago $pago): View
    {
        abort_unless(
            auth()->check()
                && auth()->user()->can('administrar'),
            403
        );

        abort_unless(
            $pago->proveedor === 'simulador'
                && $pago->estado === 'aprobado',
            404
        );

        $reserva = Reserva::findOrFail($pago->reserva_id);

        $paquete = Paquete::findOrFail($reserva->paquete_id);

        $cliente = User::findOrFail($reserva->user_id);

        return view('pagos.comprobante', compact(
            'pago',
            'reserva',
            'paquete',
            'cliente'
        ));
    }
}

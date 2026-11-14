<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FacturacionController extends Controller
{
    /**
     * Listado general de facturación y comprobantes.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $estado = (string) $request->query('estado', '');

        $consulta = DB::table('reservas')
            ->join('users', 'users.id', '=', 'reservas.user_id')
            ->join('paquetes', 'paquetes.id', '=', 'reservas.paquete_id')
            ->select(
                'reservas.*',
                'users.name as cliente_nombre',
                'users.email as cliente_email',
                'paquetes.titulo as paquete_titulo',
                'paquetes.moneda as paquete_moneda'
            );

        if ($busqueda !== '') {
            $consulta->where(function ($query) use ($busqueda) {
                $query->where('users.name', 'like', "%{$busqueda}%")
                      ->orWhere('users.email', 'like', "%{$busqueda}%")
                      ->orWhere('paquetes.titulo', 'like', "%{$busqueda}%");

                if (ctype_digit($busqueda)) {
                    $query->orWhere('reservas.id', '=', (int) $busqueda);
                }
            });
        }

        if (in_array($estado, ['confirmada', 'pendiente', 'cancelada'], true)) {
            $consulta->where('reservas.estado', $estado);
        }

        $comprobantes = $consulta
            ->orderByDesc('reservas.created_at')
            ->paginate(10)
            ->withQueryString();

        // Métricas financieras desde la BD
        $totalCobrado = (float) DB::table('reservas')
            ->where('estado', 'confirmada')
            ->sum('total');

        $totalPendiente = (float) DB::table('reservas')
            ->where('estado', 'pendiente')
            ->sum('total');

        $totalAnulado = (float) DB::table('reservas')
            ->where('estado', 'cancelada')
            ->sum('total');

        $totalOperaciones = DB::table('reservas')->count();

        return view('admin.facturacion.index', compact(
            'comprobantes',
            'busqueda',
            'estado',
            'totalCobrado',
            'totalPendiente',
            'totalAnulado',
            'totalOperaciones'
        ));
    }
}

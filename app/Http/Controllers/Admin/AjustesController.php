<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AjustesController extends Controller
{
    /**
     * Mostrar la configuración general del sistema.
     */
    public function index(): View
    {
        $admin = auth()->user();

        // Datos del sistema
        $sistema = [
            'php_version'      => PHP_VERSION,
            'laravel_version'  => app()->version(),
            'app_env'          => config('app.env'),
            'app_debug'        => config('app.debug') ? 'Activado' : 'Desactivado',
            'app_timezone'     => config('app.timezone', 'America/Lima'),
            'db_connection'    => config('database.default'),
            'db_name'          => config('database.connections.mysql.database'),
            'session_driver'   => config('session.driver'),
        ];

        // Configuración de la agencia guardada o valores por defecto
        $agencia = session('ajustes_agencia', [
            'nombre'           => 'World Travel S.A.C.',
            'ruc'              => '20601234567',
            'email_contacto'   => 'contacto@worldtravel.com',
            'telefono'         => '+51 1 456-7890',
            'direccion'        => 'Av. Larco 1234, Miraflores, Lima, Perú',
            'moneda_principal' => 'USD ($)',
            'tasa_igv'         => '18',
        ]);

        return view('admin.ajustes.index', compact('admin', 'sistema', 'agencia'));
    }

    /**
     * Guardar cambios en los ajustes de la agencia.
     */
    public function update(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre'         => 'required|string|max:120',
            'ruc'            => 'required|string|max:20',
            'email_contacto' => 'required|email|max:120',
            'telefono'       => 'required|string|max:30',
            'direccion'      => 'required|string|max:200',
            'tasa_igv'       => 'required|numeric|min:0|max:100',
        ]);

        $datos['moneda_principal'] = 'USD ($)';

        session(['ajustes_agencia' => $datos]);

        return redirect()
            ->route('admin.ajustes.index')
            ->with('success', 'Ajustes del sistema actualizados correctamente.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    /**
     * Mostrar los clientes registrados.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));

        $consulta = User::query()
            ->where('rol', 'cliente');

        if ($busqueda !== '') {
            $consulta->where(function ($query) use ($busqueda) {
                $query->where('name', 'like', '%' . $busqueda . '%')
                    ->orWhere('email', 'like', '%' . $busqueda . '%');
            });
        }

        $clientes = $consulta
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $totalClientes = User::where('rol', 'cliente')->count();

        return view('admin.clientes.index', [
            'clientes' => $clientes,
            'totalClientes' => $totalClientes,
            'busqueda' => $busqueda,
        ]);
    }

    /**
     * Mostrar la ficha de un cliente.
     */
    public function show(User $cliente): View
    {
        // Impedir consultar administradores desde esta sección.
        abort_unless($cliente->rol === 'cliente', 404);

        return view('admin.clientes.show', [
            'cliente' => $cliente,
        ]);
    }
}

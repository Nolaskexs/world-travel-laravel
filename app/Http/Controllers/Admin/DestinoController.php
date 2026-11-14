<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destino;
use App\Models\Paquete;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DestinoController extends Controller
{
    /**
     * Listar y buscar destinos turísticos.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));

        $consulta = Destino::query();

        if ($busqueda !== '') {
            $consulta->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('pais', 'like', "%{$busqueda}%")
                  ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        $destinos = $consulta->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        // Contar paquetes por destino
        $totalDestinos = Destino::count();
        $totalPaises = Destino::distinct('pais')->count('pais');

        return view('admin.destinos.index', compact(
            'destinos',
            'busqueda',
            'totalDestinos',
            'totalPaises'
        ));
    }

    /**
     * Formulario para registrar un destino.
     */
    public function create(): View
    {
        return view('admin.destinos.create');
    }

    /**
     * Guardar el nuevo destino en la BD.
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre'      => 'required|string|max:100|unique:destinos,nombre',
            'pais'        => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:2000',
            'imagen'      => 'nullable|string|max:500',
        ]);

        Destino::create($datos);

        return redirect()
            ->route('admin.destinos.index')
            ->with('success', 'Destino turístico registrado correctamente.');
    }

    /**
     * Formulario de edición de un destino.
     */
    public function edit(Destino $destino): View
    {
        return view('admin.destinos.edit', compact('destino'));
    }

    /**
     * Guardar cambios en el destino.
     */
    public function update(Request $request, Destino $destino): RedirectResponse
    {
        $datos = $request->validate([
            'nombre'      => 'required|string|max:100|unique:destinos,nombre,' . $destino->id,
            'pais'        => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:2000',
            'imagen'      => 'nullable|string|max:500',
        ]);

        $destino->update($datos);

        return redirect()
            ->route('admin.destinos.index')
            ->with('success', 'Destino turístico actualizado correctamente.');
    }

    /**
     * Eliminar un destino turístico.
     */
    public function destroy(Destino $destino): RedirectResponse
    {
        // Verificar si existen paquetes asociados al nombre de este destino
        $paquetesAsociados = Paquete::where('destino', $destino->nombre)->count();

        if ($paquetesAsociados > 0) {
            return redirect()
                ->route('admin.destinos.index')
                ->with(
                    'error',
                    "No se puede eliminar {$destino->nombre} porque tiene {$paquetesAsociados} paquetes turísticos asociados."
                );
        }

        $destino->delete();

        return redirect()
            ->route('admin.destinos.index')
            ->with('success', 'Destino eliminado correctamente.');
    }
}

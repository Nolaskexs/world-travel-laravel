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
     * Respeta la integridad referencial (Regla 4.3 de Tecsup).
     */
    public function destroy(Request $request, Destino $destino): RedirectResponse
    {
        // Verificar si existen paquetes asociados al nombre o ID de este destino
        $paquetesQuery = Paquete::where('destino', $destino->nombre)
            ->orWhere('destino_id', $destino->id);
        $paquetesAsociados = $paquetesQuery->count();

        if ($paquetesAsociados > 0) {
            // Si el administrador solicita forzar la desvinculación
            if ($request->boolean('forzar')) {
                Paquete::where('destino', $destino->nombre)
                    ->orWhere('destino_id', $destino->id)
                    ->update([
                        'destino_id' => null,
                        'destino'    => 'Destino por definir',
                    ]);

                $destino->delete();

                return redirect()
                    ->route('admin.destinos.index')
                    ->with('success', "Destino '{$destino->nombre}' eliminado. Se desvincularon {$paquetesAsociados} paquetes turísticos para proteger la integridad de los datos.");
            }

            return redirect()
                ->route('admin.destinos.index')
                ->with(
                    'error',
                    "No se puede eliminar {$destino->nombre} porque tiene {$paquetesAsociados} paquete(s) turístico(s) asociado(s). Esto protege la integridad referencial de la BD. Para eliminarlo, puedes desvincular primero los paquetes o probar eliminando un destino sin paquetes como Bali o Bangkok."
                );
        }

        $destino->delete();

        return redirect()
            ->route('admin.destinos.index')
            ->with('success', 'Destino eliminado correctamente.');
    }
}

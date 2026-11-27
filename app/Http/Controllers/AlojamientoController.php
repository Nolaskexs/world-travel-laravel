<?php

namespace App\Http\Controllers;

use App\Models\Alojamiento;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlojamientoController extends Controller
{
    /**
     * Catálogo de alojamientos alimentado desde la base de datos.
     */
    public function index(Request $request): View
    {
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'tipo' => ['nullable', 'in:hotel,resort,hostal'],
        ]);

        $consulta = Alojamiento::where('activo', true);

        if (! empty($filtros['q'])) {
            $busqueda = $filtros['q'];

            $consulta->where(function ($q) use ($busqueda): void {
                $q->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('ubicacion', 'like', "%{$busqueda}%")
                    ->orWhere('tipo', 'like', "%{$busqueda}%")
                    ->orWhere('palabras_clave', 'like', "%{$busqueda}%");
            });
        }

        if (! empty($filtros['tipo'])) {
            $consulta->where('categoria', $filtros['tipo']);
        }

        return view('alojamientos', [
            'alojamientos' => $consulta->orderBy('id')->get(),
            'filtros' => $filtros,
        ]);
    }

    /**
     * Detalle de un alojamiento por su código legible.
     */
    public function show(string $codigo): View
    {
        $alojamiento = Alojamiento::where('codigo', $codigo)
            ->where('activo', true)
            ->firstOrFail();

        return view('detalle-alojamiento', compact('alojamiento'));
    }
}

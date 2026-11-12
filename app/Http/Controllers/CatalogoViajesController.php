<?php

namespace App\Http\Controllers;

use App\Models\Oferta;
use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogoViajesController extends Controller
{
    public function paquetes(Request $request): View
    {
        $filtros = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'maximo' => ['nullable', 'numeric', 'min:1', 'max:999999'],
            'continente' => ['nullable', 'in:America,Europa,Asia,Africa,Oceania'],
            'duracion' => ['nullable', 'integer', 'min:1', 'max:30'],
            'orden' => ['nullable', 'in:destacados,precio_asc,precio_desc,duracion'],
        ]);

        $consulta = Paquete::query()->where('activo', true);

        if (!empty($filtros['q'])) {
            $busqueda = $filtros['q'];
            $consulta->where(function ($q) use ($busqueda) {
                $q->where('titulo', 'like', "%{$busqueda}%")
                  ->orWhere('destino', 'like', "%{$busqueda}%")
                  ->orWhere('pais', 'like', "%{$busqueda}%");
            });
        }

        if (!empty($filtros['continente'])) {
            $consulta->where('continente', $filtros['continente']);
        }

        if (!empty($filtros['maximo'])) {
            $consulta->where('precio', '<=', $filtros['maximo']);
        }

        if (!empty($filtros['duracion'])) {
            $consulta->where('duracion_dias', '<=', $filtros['duracion']);
        }

        switch ($filtros['orden'] ?? 'destacados') {
            case 'precio_asc':
                $consulta->orderBy('precio');
                break;
            case 'precio_desc':
                $consulta->orderByDesc('precio');
                break;
            case 'duracion':
                $consulta->orderBy('duracion_dias');
                break;
            default:
                $consulta->orderByDesc('destacado')->latest();
        }

        return view('paquetes', [
            'paquetes' => $consulta->paginate(9)->withQueryString(),
            'filtros' => $filtros,
        ]);
    }

    public function ofertas(): View
    {
        $hoy = now()->toDateString();

        $ofertas = Oferta::query()
            ->with('paquete')
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->whereHas('paquete', fn ($q) => $q->where('activo', true))
            ->orderByDesc('descuento_porcentaje')
            ->get();

        return view('ofertas', compact('ofertas'));
    }

    public function detalle(Paquete $paquete): View
    {
        abort_unless($paquete->activo, 404);
        $hoy = now()->toDateString();

        $oferta = $paquete->ofertas()
            ->where('activo', true)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->orderByDesc('descuento_porcentaje')
            ->first();

        return view('detalle-paquete', compact('paquete', 'oferta'));
    }
}

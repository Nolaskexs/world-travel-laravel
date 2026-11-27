<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reclamacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReclamacionController extends Controller
{
    /**
     * Listado con filtros, búsqueda y métricas de reclamaciones.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $estado = (string) $request->query('estado', '');
        $tipo = (string) $request->query('tipo', '');

        $query = Reclamacion::query()->latest();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('codigo', 'like', "%{$q}%")
                    ->orWhere('nombre', 'like', "%{$q}%")
                    ->orWhere('documento', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('producto', 'like', "%{$q}%")
                    ->orWhere('reserva', 'like', "%{$q}%");
            });
        }

        if (in_array($estado, ['pendiente', 'en_proceso', 'atendido', 'archivado'], true)) {
            $query->where('estado', $estado);
        }

        if (in_array($tipo, ['reclamo', 'queja'], true)) {
            $query->where('tipo', $tipo);
        }

        $reclamaciones = $query->paginate(10)->withQueryString();

        // Métricas globales
        $stats = [
            'total' => Reclamacion::count(),
            'pendientes' => Reclamacion::where('estado', 'pendiente')->count(),
            'en_proceso' => Reclamacion::where('estado', 'en_proceso')->count(),
            'atendidos' => Reclamacion::where('estado', 'atendido')->count(),
        ];

        return view('admin.reclamaciones.index', compact('reclamaciones', 'stats', 'q', 'estado', 'tipo'));
    }

    /**
     * Ver el detalle completo de una hoja de reclamación.
     */
    public function show(int $id): View
    {
        $reclamacion = Reclamacion::with(['user', 'respondidoPor'])->findOrFail($id);

        return view('admin.reclamaciones.show', compact('reclamacion'));
    }

    /**
     * Actualizar estado y redactar respuesta formal.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $reclamacion = Reclamacion::findOrFail($id);

        $validated = $request->validate([
            'estado' => ['required', 'in:pendiente,en_proceso,atendido,archivado'],
            'respuesta' => ['nullable', 'string', 'max:5000'],
        ]);

        $reclamacion->estado = $validated['estado'];

        if (! empty($validated['respuesta'])) {
            $reclamacion->respuesta = $validated['respuesta'];
            $reclamacion->respondido_por = auth()->id();
            $reclamacion->fecha_respuesta = now();
        }

        $reclamacion->save();

        return redirect()
            ->route('admin.reclamaciones.show', $reclamacion->id)
            ->with('success', '¡El reclamo '.$reclamacion->codigo.' ha sido actualizado exitosamente!');
    }
}

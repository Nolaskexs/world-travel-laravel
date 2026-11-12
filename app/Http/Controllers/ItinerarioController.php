<?php

namespace App\Http\Controllers;

use App\Models\Itinerario;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ItinerarioController extends Controller
{
    // Mostrar los itinerarios del usuario autenticado.
    public function index(Request $request): View
    {
        $itinerarios = Itinerario::query()
            ->where('user_id', $request->user()->id)
            ->with('destinos')
            ->latest()
            ->get();

        return view('itinerarios', compact('itinerarios'));
    }

    // Guardar un nuevo itinerario internacional.
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:2000',

            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_fin' => 'required|date_format:Y-m-d|after_or_equal:fecha_inicio',

            'presupuesto_objetivo_usd' => 'required|numeric|min:0|max:9999999999.99',

            'destinos' => 'required|array|min:1|max:12',
            'destinos.*.pais' => 'required|string|max:120',
            'destinos.*.ciudad' => 'required|string|max:120',
            'destinos.*.fecha_llegada' => 'required|date_format:Y-m-d',
            'destinos.*.fecha_salida' => 'required|date_format:Y-m-d',
            'destinos.*.costo_estimado_usd' => 'required|numeric|min:0|max:9999999999.99',
            'destinos.*.notas' => 'nullable|string|max:2000',
        ]);

        $salidaAnterior = null;

        foreach ($datos['destinos'] as $indice => $destino) {
            if ($destino['fecha_salida'] < $destino['fecha_llegada']) {
                throw ValidationException::withMessages([
                    "destinos.$indice.fecha_salida" =>
                        'La salida no puede ser anterior a la llegada.',
                ]);
            }

            if (
                $destino['fecha_llegada'] < $datos['fecha_inicio'] ||
                $destino['fecha_salida'] > $datos['fecha_fin']
            ) {
                throw ValidationException::withMessages([
                    "destinos.$indice.fecha_llegada" =>
                        'Las fechas del destino deben estar dentro del viaje.',
                ]);
            }

            if (
                $salidaAnterior !== null &&
                $destino['fecha_llegada'] < $salidaAnterior
            ) {
                throw ValidationException::withMessages([
                    "destinos.$indice.fecha_llegada" =>
                        'Las paradas deben estar ordenadas y no superponerse.',
                ]);
            }

            $salidaAnterior = $destino['fecha_salida'];
        }

        DB::transaction(function () use ($datos, $request) {
            $itinerario = Itinerario::create([
                'user_id' => $request->user()->id,
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'] ?? null,
                'fecha_inicio' => $datos['fecha_inicio'],
                'fecha_fin' => $datos['fecha_fin'],
                'presupuesto_objetivo_usd' => $datos['presupuesto_objetivo_usd'],
                'moneda' => 'USD',
                'estado' => 'borrador',
            ]);

            foreach ($datos['destinos'] as $indice => $destino) {
                $itinerario->destinos()->create([
                    'orden' => $indice + 1,
                    'pais' => $destino['pais'],
                    'ciudad' => $destino['ciudad'],
                    'fecha_llegada' => $destino['fecha_llegada'],
                    'fecha_salida' => $destino['fecha_salida'],
                    'costo_estimado_usd' => $destino['costo_estimado_usd'],
                    'notas' => $destino['notas'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('itinerarios.index')
            ->with('success', 'Tu itinerario se guardó correctamente.');
    }
}

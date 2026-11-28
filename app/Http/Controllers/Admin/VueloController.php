<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destino;
use App\Models\Vuelo;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VueloController extends Controller
{
    /**
     * Listar y filtrar vuelos comerciales y rutas aéreas.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $aerolinea = (string) $request->query('aerolinea', '');
        $estado = (string) $request->query('estado', '');

        $query = Vuelo::with(['origen', 'destino'])->latest('id');

        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('codigo_vuelo', 'like', "%{$busqueda}%")
                    ->orWhere('aerolinea', 'like', "%{$busqueda}%")
                    ->orWhereHas('origen', fn ($dest) => $dest->where('nombre', 'like', "%{$busqueda}%")->orWhere('codigo_iata', 'like', "%{$busqueda}%"))
                    ->orWhereHas('destino', fn ($dest) => $dest->where('nombre', 'like', "%{$busqueda}%")->orWhere('codigo_iata', 'like', "%{$busqueda}%"));
            });
        }

        if ($aerolinea !== '') {
            $query->where('aerolinea', $aerolinea);
        }

        if ($estado === 'activo') {
            $query->where('activo', true);
        } elseif ($estado === 'inactivo') {
            $query->where('activo', false);
        }

        $vuelos = $query->paginate(10)->withQueryString();

        $totalVuelos = Vuelo::count();
        $aerolineasDistintas = Vuelo::distinct('aerolinea')->count('aerolinea');
        $asientosTotales = Vuelo::sum('asientos_disponibles');

        $aerolineasList = Vuelo::distinct('aerolinea')->pluck('aerolinea')->filter()->values();

        return view('admin.vuelos.index', compact(
            'vuelos',
            'busqueda',
            'aerolinea',
            'estado',
            'totalVuelos',
            'aerolineasDistintas',
            'asientosTotales',
            'aerolineasList'
        ));
    }

    /**
     * Formulario para programar un nuevo vuelo.
     */
    public function create(): View
    {
        $destinos = Destino::orderBy('nombre')->get();

        return view('admin.vuelos.create', compact('destinos'));
    }

    /**
     * Almacenar un nuevo vuelo en MySQL.
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'codigo_vuelo' => 'required|string|max:20|unique:vuelos,codigo_vuelo',
            'aerolinea' => 'required|string|max:80',
            'origen_id' => 'required|exists:destinos,id',
            'destino_id' => 'required|exists:destinos,id|different:origen_id',
            'fecha_salida' => 'required|date',
            'fecha_llegada' => 'required|date|after:fecha_salida',
            'precio_adulto' => 'required|numeric|min:0',
            'impuestos_tasas' => 'required|numeric|min:0',
            'asientos_disponibles' => 'required|integer|min:1|max:500',
            'cantidad_escalas' => 'required|integer|min:0|max:5',
            'etiqueta' => 'required|in:ninguna,el_mas_barato,mejor_opcion,menor_duracion',
            'incluye_articulo_personal' => 'nullable|boolean',
            'incluye_equipaje_mano' => 'nullable|boolean',
            'incluye_equipaje_bodega' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
        ], [
            'destino_id.different' => 'El destino debe ser diferente a la ciudad de origen.',
            'fecha_llegada.after' => 'La fecha/hora de llegada debe ser posterior a la salida.',
            'codigo_vuelo.unique' => 'Ya existe un vuelo programado con este código.',
        ]);

        $salida = Carbon::parse($datos['fecha_salida']);
        $llegada = Carbon::parse($datos['fecha_llegada']);
        $duracionMinutos = $salida->diffInMinutes($llegada);
        $diasDiferencia = $salida->diffInDays($llegada);

        $precioAdulto = (float) $datos['precio_adulto'];
        $impuestos = (float) $datos['impuestos_tasas'];
        $precioFinal = round($precioAdulto + $impuestos, 2);

        Vuelo::create([
            'codigo_vuelo' => strtoupper($datos['codigo_vuelo']),
            'aerolinea' => $datos['aerolinea'],
            'origen_id' => $datos['origen_id'],
            'destino_id' => $datos['destino_id'],
            'fecha_salida' => $datos['fecha_salida'],
            'fecha_llegada' => $datos['fecha_llegada'],
            'duracion_minutos' => $duracionMinutos,
            'dias_diferencia' => $diasDiferencia,
            'cantidad_escalas' => $datos['cantidad_escalas'],
            'precio_adulto' => $precioAdulto,
            'precio_menor' => round($precioAdulto * 0.8, 2),
            'precio_infante' => round($precioAdulto * 0.1, 2),
            'impuestos_tasas' => $impuestos,
            'precio_final' => $precioFinal,
            'moneda' => 'USD',
            'asientos_disponibles' => $datos['asientos_disponibles'],
            'etiqueta' => $datos['etiqueta'],
            'incluye_articulo_personal' => $request->boolean('incluye_articulo_personal', true),
            'incluye_equipaje_mano' => $request->boolean('incluye_equipaje_mano', true),
            'incluye_equipaje_bodega' => $request->boolean('incluye_equipaje_bodega', false),
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()
            ->route('admin.vuelos.index')
            ->with('success', "Vuelo comercial {$datos['codigo_vuelo']} programado exitosamente en el sistema.");
    }

    /**
     * Formulario de edición de un vuelo.
     */
    public function edit(Vuelo $vuelo): View
    {
        $destinos = Destino::orderBy('nombre')->get();

        return view('admin.vuelos.edit', compact('vuelo', 'destinos'));
    }

    /**
     * Actualizar los datos del vuelo.
     */
    public function update(Request $request, Vuelo $vuelo): RedirectResponse
    {
        $datos = $request->validate([
            'codigo_vuelo' => 'required|string|max:20|unique:vuelos,codigo_vuelo,'.$vuelo->id,
            'aerolinea' => 'required|string|max:80',
            'origen_id' => 'required|exists:destinos,id',
            'destino_id' => 'required|exists:destinos,id|different:origen_id',
            'fecha_salida' => 'required|date',
            'fecha_llegada' => 'required|date|after:fecha_salida',
            'precio_adulto' => 'required|numeric|min:0',
            'impuestos_tasas' => 'required|numeric|min:0',
            'asientos_disponibles' => 'required|integer|min:1|max:500',
            'cantidad_escalas' => 'required|integer|min:0|max:5',
            'etiqueta' => 'required|in:ninguna,el_mas_barato,mejor_opcion,menor_duracion',
            'incluye_articulo_personal' => 'nullable|boolean',
            'incluye_equipaje_mano' => 'nullable|boolean',
            'incluye_equipaje_bodega' => 'nullable|boolean',
            'activo' => 'nullable|boolean',
        ], [
            'destino_id.different' => 'El destino debe ser diferente a la ciudad de origen.',
            'fecha_llegada.after' => 'La fecha/hora de llegada debe ser posterior a la salida.',
        ]);

        $salida = Carbon::parse($datos['fecha_salida']);
        $llegada = Carbon::parse($datos['fecha_llegada']);
        $duracionMinutos = $salida->diffInMinutes($llegada);
        $diasDiferencia = $salida->diffInDays($llegada);

        $precioAdulto = (float) $datos['precio_adulto'];
        $impuestos = (float) $datos['impuestos_tasas'];
        $precioFinal = round($precioAdulto + $impuestos, 2);

        $vuelo->update([
            'codigo_vuelo' => strtoupper($datos['codigo_vuelo']),
            'aerolinea' => $datos['aerolinea'],
            'origen_id' => $datos['origen_id'],
            'destino_id' => $datos['destino_id'],
            'fecha_salida' => $datos['fecha_salida'],
            'fecha_llegada' => $datos['fecha_llegada'],
            'duracion_minutos' => $duracionMinutos,
            'dias_diferencia' => $diasDiferencia,
            'cantidad_escalas' => $datos['cantidad_escalas'],
            'precio_adulto' => $precioAdulto,
            'precio_menor' => round($precioAdulto * 0.8, 2),
            'precio_infante' => round($precioAdulto * 0.1, 2),
            'impuestos_tasas' => $impuestos,
            'precio_final' => $precioFinal,
            'asientos_disponibles' => $datos['asientos_disponibles'],
            'etiqueta' => $datos['etiqueta'],
            'incluye_articulo_personal' => $request->boolean('incluye_articulo_personal', true),
            'incluye_equipaje_mano' => $request->boolean('incluye_equipaje_mano', true),
            'incluye_equipaje_bodega' => $request->boolean('incluye_equipaje_bodega', false),
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()
            ->route('admin.vuelos.index')
            ->with('success', "Vuelo comercial {$vuelo->codigo_vuelo} actualizado satisfactoriamente.");
    }

    /**
     * Eliminar un vuelo protegiendo la integridad referencial.
     */
    public function destroy(Vuelo $vuelo): RedirectResponse
    {
        if ($vuelo->reservas()->exists()) {
            return redirect()
                ->route('admin.vuelos.index')
                ->with('error', "No se puede eliminar el vuelo {$vuelo->codigo_vuelo} porque tiene reservas de pasajeros confirmadas.");
        }

        $codigo = $vuelo->codigo_vuelo;
        $vuelo->delete();

        return redirect()
            ->route('admin.vuelos.index')
            ->with('success', "Vuelo {$codigo} eliminado del itinerario de vuelos.");
    }
}

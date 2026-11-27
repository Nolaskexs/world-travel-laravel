<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alojamiento;
use App\Models\Destino;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AlojamientoController extends Controller
{
    /**
     * Listado administrativo de alojamientos con filtros y estadísticas.
     */
    public function index(Request $request): View
    {
        $busqueda = trim((string) $request->query('q', ''));
        $tipo = (string) $request->query('tipo', '');
        $estado = (string) $request->query('estado', '');

        $query = Alojamiento::latest('id');

        if ($busqueda !== '') {
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('ubicacion', 'like', "%{$busqueda}%")
                    ->orWhere('codigo', 'like', "%{$busqueda}%")
                    ->orWhere('palabras_clave', 'like', "%{$busqueda}%");
            });
        }

        if ($tipo !== '') {
            $query->where('tipo', $tipo);
        }

        if ($estado === 'activo') {
            $query->where('activo', true);
        } elseif ($estado === 'inactivo') {
            $query->where('activo', false);
        }

        $alojamientos = $query->paginate(10)->withQueryString();

        $totalAlojamientos = Alojamiento::count();
        $hotelesCount = Alojamiento::where('tipo', 'hotel')->count();
        $resortsCount = Alojamiento::where('tipo', 'resort')->count();
        $hostalesCount = Alojamiento::where('tipo', 'hostal')->count();

        return view('admin.alojamientos.index', compact(
            'alojamientos',
            'busqueda',
            'tipo',
            'estado',
            'totalAlojamientos',
            'hotelesCount',
            'resortsCount',
            'hostalesCount'
        ));
    }

    /**
     * Formulario para crear un nuevo alojamiento.
     */
    public function create(): View
    {
        $destinos = Destino::orderBy('nombre')->get();

        return view('admin.alojamientos.create', compact('destinos'));
    }

    /**
     * Almacenar un nuevo alojamiento en la base de datos.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'nullable|string|max:80|unique:alojamientos,codigo',
            'destino_id' => 'nullable|exists:destinos,id',
            'tipo' => 'required|string|in:hotel,resort,hostal,boutique',
            'direccion' => 'nullable|string|max:255',
            'precio_noche' => 'required|numeric|min:0',
            'precio_anterior' => 'nullable|numeric|min:0',
            'moneda' => 'required|string|max:3',
            'puntuacion' => 'nullable|numeric|between:1,5',
            'estrellas' => 'required|integer|between:1,5',
            'habitacion' => 'nullable|string|max:150',
            'estadia' => 'nullable|string|max:100',
            'imagen' => 'nullable|string|max:500',
            'descripcion' => 'nullable|string',
            'servicios_texto' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        $codigo = ! empty($validated['codigo'])
            ? Str::slug($validated['codigo'])
            : Str::slug($validated['nombre']);

        // Asegurar unicidad del código slug
        $baseCodigo = $codigo;
        $counter = 1;
        while (Alojamiento::where('codigo', $codigo)->exists()) {
            $codigo = "{$baseCodigo}-{$counter}";
            $counter++;
        }

        $servicios = [];
        if (! empty($validated['servicios_texto'])) {
            $servicios = array_values(array_filter(array_map('trim', explode("\n", str_replace(',', "\n", $validated['servicios_texto'])))));
        }

        $ubicacion = $validated['direccion'] ?? 'Ubicación';
        if (! empty($validated['destino_id'])) {
            $dest = Destino::find($validated['destino_id']);
            if ($dest) {
                $ubicacion = $dest->nombre.', '.$dest->pais;
            }
        }

        Alojamiento::create([
            'codigo' => $codigo,
            'nombre' => $validated['nombre'],
            'tipo' => $validated['tipo'],
            'categoria' => in_array($validated['tipo'], ['hotel', 'resort', 'hostal']) ? $validated['tipo'] : 'hotel',
            'ubicacion' => $ubicacion,
            'habitacion' => $validated['habitacion'] ?? null,
            'descripcion' => $validated['descripcion'] ?? $validated['nombre'],
            'imagen' => $validated['imagen'] ?? 'icons/hotel1.jpg',
            'imagen_tarjeta' => $validated['imagen'] ?? 'icons/hotel1.jpg',
            'precio' => $validated['precio_noche'],
            'precio_anterior' => $validated['precio_anterior'] ?? null,
            'moneda_simbolo' => strtoupper($validated['moneda'] ?? 'USD') === 'PEN' ? 'S/' : 'US$',
            'puntuacion' => $validated['puntuacion'] ?? 4.5,
            'estrellas' => $validated['estrellas'],
            'servicios' => $servicios,
            'servicios_resumen' => count($servicios) ? implode(' · ', array_slice($servicios, 0, 4)) : 'Wi-Fi · Recepción 24h',
            'palabras_clave' => strtolower($validated['nombre'].' '.$ubicacion),
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()
            ->route('admin.alojamientos.index')
            ->with('success', "Alojamiento \"{$validated['nombre']}\" creado exitosamente en MySQL.");
    }

    /**
     * Mostrar detalles del alojamiento en el portal público.
     */
    public function show(string|int $id): RedirectResponse
    {
        $alojamiento = is_numeric($id)
            ? Alojamiento::findOrFail((int) $id)
            : Alojamiento::where('codigo', $id)->firstOrFail();

        return redirect()->route('alojamientos.detalle', ['codigo' => $alojamiento->codigo]);
    }

    /**
     * Formulario para editar un alojamiento existente.
     */
    public function edit(string|int $id): View
    {
        $alojamiento = is_numeric($id)
            ? Alojamiento::findOrFail((int) $id)
            : Alojamiento::where('codigo', $id)->firstOrFail();

        $destinos = Destino::orderBy('nombre')->get();

        return view('admin.alojamientos.edit', compact('alojamiento', 'destinos'));
    }

    /**
     * Actualizar los datos del alojamiento.
     */
    public function update(Request $request, string|int $id): RedirectResponse
    {
        $alojamiento = is_numeric($id)
            ? Alojamiento::findOrFail((int) $id)
            : Alojamiento::where('codigo', $id)->firstOrFail();

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'codigo' => 'required|string|max:80|unique:alojamientos,codigo,'.$alojamiento->id,
            'destino_id' => 'nullable|exists:destinos,id',
            'tipo' => 'required|string|in:hotel,resort,hostal,boutique',
            'direccion' => 'nullable|string|max:255',
            'precio_noche' => 'required|numeric|min:0',
            'precio_anterior' => 'nullable|numeric|min:0',
            'moneda' => 'required|string|max:3',
            'puntuacion' => 'nullable|numeric|between:1,5',
            'estrellas' => 'required|integer|between:1,5',
            'habitacion' => 'nullable|string|max:150',
            'estadia' => 'nullable|string|max:100',
            'imagen' => 'nullable|string|max:500',
            'descripcion' => 'nullable|string',
            'servicios_texto' => 'nullable|string',
            'activo' => 'nullable|boolean',
        ]);

        $servicios = [];
        if (! empty($validated['servicios_texto'])) {
            $servicios = array_values(array_filter(array_map('trim', explode("\n", str_replace(',', "\n", $validated['servicios_texto'])))));
        }

        $ubicacion = $validated['direccion'] ?? $alojamiento->ubicacion;
        if (! empty($validated['destino_id'])) {
            $dest = Destino::find($validated['destino_id']);
            if ($dest) {
                $ubicacion = $dest->nombre.', '.$dest->pais;
            }
        }

        $alojamiento->update([
            'codigo' => Str::slug($validated['codigo']),
            'nombre' => $validated['nombre'],
            'tipo' => $validated['tipo'],
            'categoria' => in_array($validated['tipo'], ['hotel', 'resort', 'hostal']) ? $validated['tipo'] : $alojamiento->categoria,
            'ubicacion' => $ubicacion,
            'habitacion' => $validated['habitacion'] ?? $alojamiento->habitacion,
            'descripcion' => $validated['descripcion'] ?? $alojamiento->descripcion,
            'imagen' => $validated['imagen'] ?? $alojamiento->imagen,
            'imagen_tarjeta' => $validated['imagen'] ?? $alojamiento->imagen_tarjeta,
            'precio' => $validated['precio_noche'],
            'precio_anterior' => $validated['precio_anterior'] ?? $alojamiento->precio_anterior,
            'moneda_simbolo' => strtoupper($validated['moneda'] ?? 'USD') === 'PEN' ? 'S/' : 'US$',
            'puntuacion' => $validated['puntuacion'] ?? $alojamiento->puntuacion,
            'estrellas' => $validated['estrellas'],
            'servicios' => $servicios,
            'servicios_resumen' => count($servicios) ? implode(' · ', array_slice($servicios, 0, 4)) : $alojamiento->servicios_resumen,
            'palabras_clave' => strtolower($validated['nombre'].' '.$ubicacion),
            'activo' => $request->boolean('activo', true),
        ]);

        return redirect()
            ->route('admin.alojamientos.index')
            ->with('success', "Alojamiento \"{$alojamiento->nombre}\" actualizado correctamente en MySQL.");
    }

    /**
     * Eliminar un alojamiento de la base de datos.
     */
    public function destroy(string|int $id): RedirectResponse
    {
        $alojamiento = is_numeric($id)
            ? Alojamiento::findOrFail((int) $id)
            : Alojamiento::where('codigo', $id)->firstOrFail();
        $nombre = $alojamiento->nombre;
        $alojamiento->delete();

        return redirect()
            ->route('admin.alojamientos.index')
            ->with('success', "Alojamiento \"{$nombre}\" eliminado satisfactoriamente.");
    }
}

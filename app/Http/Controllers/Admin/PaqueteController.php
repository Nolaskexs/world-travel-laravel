<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destino;
use App\Models\Paquete;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;

class PaqueteController extends Controller
{
    // READ: Mostrar todos los paquetes
    public function index(): View
    {
        $paquetes = Paquete::orderByDesc('created_at')
            ->paginate(10);

        return view('admin.paquetes.index', compact('paquetes'));
    }

    // CREATE: Mostrar el formulario
    public function create(): View
    {
        $destinos = Destino::orderBy('nombre')->get();
        return view('admin.paquetes.create', compact('destinos'));
    }

    // CREATE: Guardar el paquete en MySQL
    public function store(Request $request): RedirectResponse
    {
        $datos = $this->validarPaquete($request);

        Paquete::create($datos);

        return redirect()
            ->route('admin.paquetes.index')
            ->with('success', 'Paquete registrado correctamente.');
    }

    // UPDATE: Mostrar formulario de edición
    public function edit(Paquete $paquete): View
    {
        $destinos = Destino::orderBy('nombre')->get();
        return view('admin.paquetes.edit', compact('paquete', 'destinos'));
    }

    // UPDATE: Guardar las modificaciones
    public function update(
        Request $request,
        Paquete $paquete
    ): RedirectResponse {
        $datos = $this->validarPaquete($request);

        $paquete->update($datos);

        return redirect()
            ->route('admin.paquetes.index')
            ->with('success', 'Paquete actualizado correctamente.');
    }

    // Validaciones compartidas entre registrar y editar
    private function validarPaquete(Request $request): array
    {
        $datos = $request->validate([
            'titulo' => [
                'required',
                'string',
                'max:150',
            ],
            'destino' => [
                'required',
                'string',
                'max:100',
            ],
            'pais' => [
                'required',
                'string',
                'max:100',
            ],
            'continente' => [
                'required',
                Rule::in([
                    'America',
                    'Europa',
                    'Asia',
                    'Africa',
                    'Oceania',
                ]),
            ],
            'descripcion' => [
                'required',
                'string',
                'max:5000',
            ],
            'duracion_dias' => [
                'required',
                'integer',
                'min:1',
                'max:255',
            ],
            'precio' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'imagen_url' => [
                'nullable',
                'url',
                'max:500',
            ],
        ]);

        // World Travel utiliza USD para todos los paquetes.
        $datos['moneda'] = 'USD';

        // Asociar la llave foránea destino_id si existe el destino registrado (Regla 4.5)
        $destinoObj = Destino::where('nombre', $datos['destino'])->first();
        if ($destinoObj) {
            $datos['destino_id'] = $destinoObj->id;
        }

        // Casillas de verificación del formulario.
        $datos['incluye_vuelo'] = $request->boolean('incluye_vuelo');
        $datos['incluye_hotel'] = $request->boolean('incluye_hotel');
        $datos['incluye_tour'] = $request->boolean('incluye_tour');
        $datos['destacado'] = $request->boolean('destacado');
        $datos['activo'] = $request->boolean('activo');

        return $datos;
    }


/**
 * DELETE: Eliminar un paquete turístico.
 */
    public function destroy(Paquete $paquete): RedirectResponse
    {
        // No eliminar paquetes que tengan ofertas relacionadas.
        if ($paquete->ofertas()->exists()) {
            return redirect()
                ->route('admin.paquetes.index')
                ->with(
                    'error',
                    'No se puede eliminar este paquete porque tiene ofertas asociadas.'
                );
        }

        // No eliminar paquetes que tengan reservas relacionadas.
        if (
            Schema::hasTable('reservas') &&
            Schema::hasColumn('reservas', 'paquete_id') &&
            DB::table('reservas')
                ->where('paquete_id', $paquete->id)
                ->exists()
        ) {
            return redirect()
                ->route('admin.paquetes.index')
                ->with(
                    'error',
                    'No se puede eliminar este paquete porque tiene reservas registradas.'
                );
        }

        try {
            // Eliminar el paquete de MySQL.
            $paquete->delete();
        } catch (QueryException $e) {
            report($e);

            return redirect()
                ->route('admin.paquetes.index')
                ->with(
                    'error',
                    'No se puede eliminar el paquete porque está relacionado con otros registros.'
                );
        }

        return redirect()
            ->route('admin.paquetes.index')
            ->with(
                'success',
                'Paquete turístico eliminado correctamente.'
            );
    }

    
}

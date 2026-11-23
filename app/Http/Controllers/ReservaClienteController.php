<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\ReservaPasajero;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaClienteController extends Controller
{
    /**
     * Mostrar el formulario del proceso de reserva (Checkout).
     */
    public function create(Request $request)
    {
        $paquetes = Paquete::where('activo', true)->orderBy('titulo')->get();

        $paqueteId = $request->query('paquete_id');
        $paqueteSeleccionado = null;

        $hotelCode = $request->query('hotel');
        $hotelNombre = $request->query('hotel_nombre');
        $hotelUbicacion = $request->query('hotel_ubicacion');
        $hotelNoches = (int) $request->query('noches', 0);

        if ($hotelCode && !$paqueteId) {
            $mapHotelDestino = [
                'tokio-ritz' => 'Tokio',
                'cartagena-oz' => 'Cartagena',
                'madrid-riu' => 'Madrid',
                'cusco-monasterio' => 'Cusco',
                'barcelona-arts' => 'Madrid',
                'lima-miraflores' => 'Ica',
                'cusco' => 'Cusco',
                'lima' => 'Ica',
                'arequipa' => 'Arequipa',
            ];
            $destinoBuscado = $mapHotelDestino[$hotelCode] ?? null;
            if ($destinoBuscado) {
                $paqueteEncontrado = $paquetes->firstWhere('destino', $destinoBuscado);
                if ($paqueteEncontrado) {
                    $paqueteSeleccionado = $paqueteEncontrado;
                }
            }
        }

        if ($paqueteId) {
            $paqueteSeleccionado = $paquetes->firstWhere('id', (int) $paqueteId);
        }

        // Si no se especificó o no se encontró, tomar el primer paquete disponible
        if (!$paqueteSeleccionado) {
            $paqueteSeleccionado = $paquetes->first();
        }

        $fechaViaje = $request->query('fecha_viaje', $request->query('entrada', $request->query('salida', now()->addDays(14)->format('Y-m-d'))));
        $numPasajeros = max(1, (int) $request->query('num_pasajeros', $request->query('huespedes', $request->query('adults', 1))));
        $asientos = (string) $request->query('asientos', '');
        $precioUnitario = (float) ($paqueteSeleccionado->precio ?? 350.00);
        $totalEstimado = (float) $request->query('total', $precioUnitario * $numPasajeros);

        $user = Auth::user();

        return view('confirmar-reserva', compact(
            'paquetes',
            'paqueteSeleccionado',
            'fechaViaje',
            'numPasajeros',
            'asientos',
            'totalEstimado',
            'user',
            'hotelNombre',
            'hotelUbicacion',
            'hotelNoches'
        ));
    }

    /**
     * Registrar la reserva y sus pasajeros en la base de datos (MySQL).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'        => 'required|string|min:3|max:100',
            'email'         => 'required|email|max:150',
            'telefono'      => 'nullable|string|max:30',
            'documento'     => 'required|string|min:4|max:30',
            'paquete_id'    => 'required|exists:paquetes,id',
            'fecha_viaje'   => 'required|date',
            'num_pasajeros' => 'required|integer|min:1|max:10',
            'total'         => 'required|numeric|min:0',
        ]);


        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para reservar.');
        }

        if (!in_array(Auth::user()->rol, ['cliente', 'admin'])) {
            return back()
                ->withErrors([
                    'cuenta' => 'Debes iniciar sesión como cliente o administrador para reservar.'
                ])
                ->withInput();
        }

        $userId = Auth::id();

        $paquete = Paquete::where('activo', true)
            ->findOrFail($validated['paquete_id']);

        $totalCalculado = (
            (int) round(((float) $paquete->precio) * 100)
            * (int) $validated['num_pasajeros']
        ) / 100;


        // 1. Guardar en la tabla reservas
        $reserva = Reserva::create([
            'user_id'       => $userId,
            'paquete_id'    => $validated['paquete_id'],
            'fecha_viaje'   => $validated['fecha_viaje'],
            'num_pasajeros' => $validated['num_pasajeros'],
                    'total' => $totalCalculado,
            'estado'        => 'pendiente',
        ]);

        // Procesar asientos si fueron seleccionados en el mapa del avión
        $asientosInput = $request->input('asientos', '');
        $asientosLista = [];
        if (!empty($asientosInput)) {
            $asientosLista = array_values(array_filter(array_map('trim', explode(',', (string) $asientosInput))));
        }

        // Si no se eligieron suficientes asientos en el mapa, auto-asignar asientos reales disponibles
        if (count($asientosLista) < (int) $validated['num_pasajeros']) {
            $asientosOcupados = ReservaPasajero::whereNotNull('asiento')->where('asiento', '!=', '')->pluck('asiento')->all();
            $filas = range(10, 35);
            $letras = ['A', 'B', 'C', 'D', 'E', 'F'];
            foreach ($filas as $fila) {
                foreach ($letras as $letra) {
                    $codigo = $fila . $letra;
                    if (!in_array($codigo, $asientosOcupados, true) && !in_array($codigo, $asientosLista, true)) {
                        $asientosLista[] = $codigo;
                        if (count($asientosLista) >= (int) $validated['num_pasajeros']) {
                            break 2;
                        }
                    }
                }
            }
        }

        // 2. Guardar en la tabla reserva_pasajeros (Pasajero titular)
        ReservaPasajero::create([
            'reserva_id' => $reserva->id,
            'nombre'     => $validated['nombre'],
            'documento'  => $validated['documento'],
            'asiento'    => $asientosLista[0] ?? '14A',
        ]);

        // Pasajeros acompañantes si num_pasajeros > 1
        for ($i = 2; $i <= $validated['num_pasajeros']; $i++) {
            ReservaPasajero::create([
                'reserva_id' => $reserva->id,
                'nombre'     => "Pasajero acompañante {$i} ({$validated['nombre']})",
                'documento'  => 'DNI-' . rand(10000000, 99999999),
                'asiento'    => $asientosLista[$i - 1] ?? ('14' . chr(64 + $i)),
            ]);
        }

        $asientosTexto = implode(', ', array_slice($asientosLista, 0, (int) $validated['num_pasajeros']));

        if (Auth::user()?->esAdmin()) {
            return redirect()
                ->route('admin.reservas.show', $reserva->id)
                ->with('success', "¡Reserva #{$reserva->id} registrada exitosamente en la base de datos! Asientos asignados: {$asientosTexto}.");
        }

        return redirect()
            ->route('pagos.show', $reserva->id)
            ->with(
                'success',
                "Reserva #{$reserva->id} registrada correctamente. Asientos asignados: {$asientosTexto}. Continúa con el pago."
            );

    }
}

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

        if ($paqueteId) {
            $paqueteSeleccionado = $paquetes->firstWhere('id', (int) $paqueteId);
        }

        // Si no se especificó o no se encontró, tomar el primer paquete disponible
        if (!$paqueteSeleccionado) {
            $paqueteSeleccionado = $paquetes->first();
        }

        $fechaViaje = $request->query('fecha_viaje', $request->query('salida', now()->addDays(14)->format('Y-m-d')));
        $numPasajeros = max(1, (int) $request->query('num_pasajeros', $request->query('adults', 1)));
        $precioUnitario = (float) ($paqueteSeleccionado->precio ?? 350.00);
        $totalEstimado = $precioUnitario * $numPasajeros;

        $user = Auth::user();

        return view('confirmar-reserva', compact(
            'paquetes',
            'paqueteSeleccionado',
            'fechaViaje',
            'numPasajeros',
            'totalEstimado',
            'user'
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

        // Determinar el cliente asociado: usuario autenticado o cliente demo
        $userId = Auth::id();
        if (!$userId) {
            $clienteDemo = User::where('email', 'cliente@worldtravel.com')->first();
            $userId = $clienteDemo ? $clienteDemo->id : (User::first()->id ?? 1);
        }

        // 1. Guardar en la tabla reservas
        $reserva = Reserva::create([
            'user_id'       => $userId,
            'paquete_id'    => $validated['paquete_id'],
            'fecha_viaje'   => $validated['fecha_viaje'],
            'num_pasajeros' => $validated['num_pasajeros'],
            'total'         => $validated['total'],
            'estado'        => 'pendiente',
        ]);

        // 2. Guardar en la tabla reserva_pasajeros (Pasajero titular)
        ReservaPasajero::create([
            'reserva_id' => $reserva->id,
            'nombre'     => $validated['nombre'],
            'documento'  => $validated['documento'],
        ]);

        // Pasajeros acompañantes si num_pasajeros > 1
        for ($i = 2; $i <= $validated['num_pasajeros']; $i++) {
            ReservaPasajero::create([
                'reserva_id' => $reserva->id,
                'nombre'     => "Pasajero acompañante {$i} ({$validated['nombre']})",
                'documento'  => 'DNI-' . rand(10000000, 99999999),
            ]);
        }

        // Si el usuario no estaba autenticado, iniciar sesión automáticamente como cliente
        if (!Auth::check()) {
            $userToLogin = User::find($userId);
            if ($userToLogin) {
                Auth::login($userToLogin);
            }
        }

        return redirect()
            ->route('dashboard')
            ->with('success', "¡Tu reserva #{$reserva->id} ha sido registrada con éxito en la base de datos! Ya puedes visualizarla en tu panel.");
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\ReservaPasajero;
use App\Models\Vuelo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AsientoController extends Controller
{
    /**
     * Muestra la interfaz de selección de asientos.
     * Funciona tanto para el Flujo de Compra como para el Check-in desde el Dashboard.
     */
    public function index(Request $request): View
    {
        $reservaId = $request->query('reserva_id');
        $reserva = null;
        $pasajeros = [];
        $asientosOcupados = ['1A', '2C', '3D', '12A', '14F', '15D', '18J', '22C', '24L', '27H', '30A', '30C', '33F'];

        // CASO A: Check-in de una reserva existente
        if ($reservaId) {
            $user = auth()->user();
            $reserva = Reserva::with(['paquete', 'pasajeros'])->findOrFail($reservaId);

            // Validar autorización: el usuario debe ser dueño de la reserva o admin
            if ($user && !$user->esAdmin() && $reserva->user_id !== $user->id) {
                abort(403, 'No tienes permiso para acceder a esta reserva.');
            }

            $paquete = $reserva->paquete;
            $destinoNombre = $paquete ? $paquete->destino : 'Destino';
            $paisNombre = $paquete ? $paquete->pais : 'Mundo';

            $vuelo = (object) [
                'id'          => $reserva->id,
                'avion'       => 'World Travel',
                'origen'      => 'Lima (LIM)',
                'destino'     => "{$destinoNombre} ({$paisNombre})",
                'numero'      => 'WT-' . str_pad((string) $reserva->id, 4, '0', STR_PAD_LEFT),
                'precio_base' => 0.00, // En check-in la tarifa base ya fue pagada
            ];

            // Pasajeros de la reserva real
            $adultos = 0;
            $menores = 0;
            $infantes = 0;

            if ($reserva->pasajeros->isNotEmpty()) {
                foreach ($reserva->pasajeros as $index => $p) {
                    $pasajeros[] = [
                        'id'          => $p->id,
                        'nombre'      => $p->nombre,
                        'codigo'      => 'P' . ($index + 1),
                        'asiento'     => $p->asiento,
                    ];
                }
                $adultos = count($pasajeros);
            } else {
                for ($i = 1; $i <= $reserva->num_pasajeros; $i++) {
                    $pasajeros[] = [
                        'id'          => null,
                        'nombre'      => "Pasajero $i",
                        'codigo'      => "P$i",
                        'asiento'     => null,
                    ];
                }
                $adultos = $reserva->num_pasajeros;
            }

            // Asientos ya ocupados por otras reservas en el sistema
            $asientosEnBD = ReservaPasajero::where('reserva_id', '!=', $reserva->id)
                ->whereNotNull('asiento')
                ->pluck('asiento')
                ->all();
            $asientosOcupados = array_unique(array_merge($asientosOcupados, $asientosEnBD));

            $baseTotal = 0;
        }
        // CASO B: Flujo de compra nuevo (desde search.blade.php o paquete)
        else {
            $vueloId    = $request->query('vuelo_id');
            $paqueteId  = $request->query('paquete_id');
            $adultos    = max(1, (int) $request->query('adultos', 1));
            $menores    = max(0, (int) $request->query('menores', 0));
            $infantes   = max(0, (int) $request->query('infantes', 0));
            $fechaViaje = $request->query('fecha_viaje', now()->addDays(7)->format('Y-m-d'));

            $vueloObj = null;
            if ($vueloId) {
                $vueloObj = Vuelo::with(['origen', 'destino'])->find($vueloId);
            }

            $paqueteObj = null;
            if ($paqueteId) {
                $paqueteObj = Paquete::find($paqueteId);
            }

            // Prioridad 1: parámetros explícitos enviados desde el buscador/reserva
            $qOrigin    = $request->query('origin');
            $qDest      = $request->query('destination');
            $qOrigIata  = $request->query('origin_iata');
            $qDestIata  = $request->query('destination_iata');
            $qCodigo    = $request->query('codigo_vuelo');
            $qPrecio    = $request->query('precio');

            if (!empty($qOrigin) && !empty($qDest)) {
                $origenTexto  = !empty($qOrigIata) ? "{$qOrigin} ({$qOrigIata})" : $qOrigin;
                $destinoTexto = !empty($qDestIata) ? "{$qDest} ({$qDestIata})" : $qDest;
                $codigoVuelo  = !empty($qCodigo) ? $qCodigo : 'WT-8183';
                $precioBase   = $qPrecio ? (float) $qPrecio : 389.00;
            } elseif ($vueloObj && $vueloObj->origen && $vueloObj->destino) {
                $origIata     = $vueloObj->origen->codigo_iata ?? $vueloObj->origen->iata ?? 'LIM';
                $destIata     = $vueloObj->destino->codigo_iata ?? $vueloObj->destino->iata ?? '';
                $origenTexto  = "{$vueloObj->origen->nombre} ({$origIata})";
                $destinoTexto = $destIata ? "{$vueloObj->destino->nombre} ({$destIata})" : $vueloObj->destino->nombre;
                $codigoVuelo  = $vueloObj->codigo_vuelo;
                $precioBase   = (float) $vueloObj->precio_adulto;
            } elseif ($paqueteObj) {
                $origenTexto  = "Lima (LIM)";
                $destinoTexto = "{$paqueteObj->destino} ({$paqueteObj->pais})";
                $codigoVuelo  = 'WT-' . ($paqueteObj->id + 100);
                $precioBase   = (float) $paqueteObj->precio;
            } else {
                $origenTexto  = "Lima (LIM)";
                $destinoTexto = "Tokio (HND)";
                $codigoVuelo  = 'WT-8183';
                $precioBase   = 1250.00;
            }

            $vuelo = (object) [
                'id'          => $vueloId ?: 1,
                'avion'       => 'World Travel',
                'origen'      => $origenTexto,
                'destino'     => $destinoTexto,
                'numero'      => $codigoVuelo,
                'precio_base' => $precioBase,
            ];

            for ($i = 1; $i <= $adultos; $i++) {
                $pasajeros[] = ['id' => null, 'nombre' => "Adulto $i", 'codigo' => "A$i", 'asiento' => null];
            }
            for ($i = 1; $i <= $menores; $i++) {
                $pasajeros[] = ['id' => null, 'nombre' => "Menor $i",  'codigo' => "M$i", 'asiento' => null];
            }

            $baseTotal = $precioBase * count($pasajeros);
        }

        $tarifas = ['xl' => 35, 'biz' => 120];
        $moneda  = 'USD';

        return view('asientos', compact(
            'vuelo',
            'reserva',
            'pasajeros',
            'adultos',
            'menores',
            'infantes',
            'asientosOcupados',
            'tarifas',
            'baseTotal',
            'moneda'
        ));
    }

    /**
     * Procesa la selección de asientos:
     * - Si proviene de un Check-in: guarda los asientos en MySQL y vuelve al Dashboard.
     * - Si proviene del flujo de compra: redirige a la confirmación de reserva con los asientos seleccionados.
     */
    public function guardar(Request $request): RedirectResponse
    {
        $reservaId = $request->input('reserva_id');
        $asientos  = $request->input('asientos', []); // array asociativo [0 => '12A', 1 => '12B']
        $asientosStr = is_array($asientos) ? implode(', ', array_filter($asientos)) : (string) $asientos;

        // ACCIÓN 1: Guardar Check-in en reserva existente
        if ($reservaId) {
            $user = auth()->user();
            $reserva = Reserva::with('pasajeros')->findOrFail($reservaId);

            if ($user && !$user->esAdmin() && $reserva->user_id !== $user->id) {
                abort(403);
            }

            // Actualizar los asientos de los pasajeros
            if ($reserva->pasajeros->isNotEmpty()) {
                foreach ($reserva->pasajeros as $index => $pasajero) {
                    if (isset($asientos[$index])) {
                        $pasajero->asiento = $asientos[$index];
                        $pasajero->save();
                    }
                }
            } else {
                // Si la reserva no tenía pasajeros desglosados en reserva_pasajeros, crearlos
                foreach ($asientos as $index => $asientoCodigo) {
                    ReservaPasajero::create([
                        'reserva_id' => $reserva->id,
                        'nombre'     => $user ? $user->name : "Pasajero " . ($index + 1),
                        'documento'  => $user?->dni ?? ('DOC-' . rand(10000000, 99999999)),
                        'asiento'    => $asientoCodigo,
                    ]);
                }
            }

            return redirect()
                ->route('dashboard')
                ->with('success', "¡Check-in realizado con éxito! Asientos asignados: {$asientosStr}.");
        }

        // ACCIÓN 2: Continuar al paso final de Confirmación y Pago
        $paqueteId   = $request->input('paquete_id');
        $vueloId     = $request->input('vuelo_id');
        $fechaViaje  = $request->input('fecha_viaje');
        $numPasajeros = $request->input('num_pasajeros', count($asientos) ?: 1);

        $params = [
            'paquete_id'    => $paqueteId,
            'vuelo_id'      => $vueloId,
            'fecha_viaje'   => $fechaViaje,
            'num_pasajeros' => $numPasajeros,
            'asientos'      => $asientosStr,
        ];

        return redirect()->route('reservas.confirmar', array_filter($params));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Paquete;
use App\Models\Reserva;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PagoController extends Controller
{
    private function verificarCliente(Reserva $reserva): void
    {
        $user = Auth::user();
        abort_unless(
            $user && ($user->esAdmin() || ((int) $reserva->user_id === (int) $user->id)),
            403
        );
    }

    public function show(Reserva $reserva): View
    {
        $this->verificarCliente($reserva);

        $paquete = Paquete::findOrFail($reserva->paquete_id);
        $moneda = strtoupper($paquete->moneda ?: 'USD');

        $pagos = Pago::where('reserva_id', $reserva->id)
            ->latest()
            ->get();

        $pagoAprobado = $pagos->first(
            fn ($pago) => $pago->estado === 'aprobado'
                && $pago->proveedor === 'simulador'
        );

        $intentoId = (string) Str::uuid();

        return view('pagos.checkout', compact(
            'reserva',
            'paquete',
            'moneda',
            'pagos',
            'pagoAprobado',
            'intentoId'
        ));
    }

    public function simular(
        Request $request,
        Reserva $reserva
    ): RedirectResponse {
        // No permitir simulaciones fuera del entorno local.
        abort_unless(app()->environment('local'), 404);

        $this->verificarCliente($reserva);

        $datos = $request->validate([
            'metodo' => [
                'required',
                Rule::in([
                    'tarjeta',
                    'yape',
                    'plin',
                    'transferencia',
                ]),
            ],
            'resultado' => [
                'required',
                Rule::in(['aprobado', 'rechazado']),
            ],
            'intento_id' => 'required|uuid',
        ]);

        $pago = DB::transaction(function () use ($reserva, $datos) {
            $reservaActual = Reserva::whereKey($reserva->id)
                ->lockForUpdate()
                ->firstOrFail();

            $this->verificarCliente($reservaActual);

            // Evitar procesar dos veces el mismo formulario.
            $existente = Pago::where(
                'clave_idempotencia',
                $datos['intento_id']
            )->first();

            if ($existente) {
                return $existente;
            }

            // Evitar otra operación sobre una reserva pagada.
            $aprobado = Pago::where(
                'reserva_id',
                $reservaActual->id
            )
                ->where('estado', 'aprobado')
                ->first();

            if ($aprobado) {
                return $aprobado;
            }

            abort_unless(
                $reservaActual->estado === 'pendiente',
                409,
                'Esta reserva no está pendiente.'
            );

            $paquete = Paquete::findOrFail(
                $reservaActual->paquete_id
            );

            $moneda = strtoupper($paquete->moneda ?: 'USD');

            $pago = Pago::create([
                'reserva_id' => $reservaActual->id,
                'proveedor' => 'simulador',
                'metodo' => $datos['metodo'],
                'monto_original' => $reservaActual->total,
                'moneda_original' => $moneda,
                'monto' => $reservaActual->total,
                'moneda' => $moneda,
                'tipo_cambio' => 1,
                'estado' => $datos['resultado'],
                'clave_idempotencia' => $datos['intento_id'],
                'verificado_en' => $datos['resultado'] === 'aprobado'
                    ? now()
                    : null,
            ]);

            if ($datos['resultado'] === 'aprobado') {
                $reservaActual->update([
                    'estado' => 'confirmada',
                ]);
            }

            return $pago;
        });

        if (
            $pago->estado === 'aprobado'
            && $pago->proveedor === 'simulador'
        ) {
            return redirect()->route(
                'pagos.comprobante',
                $pago->id
            );
        }

        return redirect()
            ->route('pagos.show', $reserva->id)
            ->with(
                'error',
                'Pago rechazado en la simulación. Puedes intentar nuevamente.'
            );
    }

    public function comprobante(Pago $pago): View
    {
        abort_unless($pago->proveedor === 'simulador', 404);
        abort_unless($pago->estado === 'aprobado', 404);

        $reserva = Reserva::findOrFail($pago->reserva_id);

        $this->verificarCliente($reserva);

        $paquete = Paquete::findOrFail($reserva->paquete_id);

        return view('pagos.comprobante', compact(
            'pago',
            'reserva',
            'paquete'
        ));
    }
}

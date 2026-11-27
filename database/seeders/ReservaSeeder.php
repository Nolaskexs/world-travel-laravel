<?php

namespace Database\Seeders;

use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\ReservaPasajero;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('rol', 'cliente')->first();
        if (! $cliente) {
            $cliente = User::create([
                'name' => 'Cliente Demo',
                'email' => 'cliente@worldtravel.com',
                'password' => bcrypt('Cliente12345'),
                'rol' => 'cliente',
            ]);
        }

        $paqueteRoma = Paquete::where('destino', 'Roma')->first() ?? Paquete::first();
        $paqueteCusco = Paquete::where('destino', 'Cusco')->first() ?? Paquete::skip(1)->first();

        // 1. Reserva confirmada - Roma
        if ($paqueteRoma) {
            $r1 = Reserva::updateOrCreate(
                ['id' => 1],
                [
                    'user_id' => $cliente->id,
                    'paquete_id' => $paqueteRoma->id,
                    'fecha_viaje' => now()->addDays(15)->toDateString(),
                    'num_pasajeros' => 1,
                    'total' => 1230.00,
                    'estado' => 'confirmada',
                ]
            );

            ReservaPasajero::updateOrCreate(
                ['reserva_id' => $r1->id, 'nombre' => $cliente->name],
                ['documento' => '72349182']
            );
        }

        // 2. Reserva pendiente - Cusco
        if ($paqueteCusco) {
            $r2 = Reserva::updateOrCreate(
                ['id' => 2],
                [
                    'user_id' => $cliente->id,
                    'paquete_id' => $paqueteCusco->id,
                    'fecha_viaje' => now()->addDays(35)->toDateString(),
                    'num_pasajeros' => 1,
                    'total' => 519.00,
                    'estado' => 'pendiente',
                ]
            );

            ReservaPasajero::updateOrCreate(
                ['reserva_id' => $r2->id, 'nombre' => $cliente->name],
                ['documento' => '72349182']
            );
        }

        // Eliminar cualquier pago y reserva adicional para dejar exactamente 2
        $idsValidos = [1, 2];
        if (DB::getSchemaBuilder()->hasTable('pagos')) {
            DB::table('pagos')->whereNotIn('reserva_id', $idsValidos)->delete();
        }
        ReservaPasajero::whereNotIn('reserva_id', $idsValidos)->delete();
        Reserva::whereNotIn('id', $idsValidos)->delete();
    }
}

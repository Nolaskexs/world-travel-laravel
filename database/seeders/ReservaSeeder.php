<?php

namespace Database\Seeders;

use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\ReservaPasajero;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $cliente = User::where('rol', 'cliente')->first();
        if (!$cliente) {
            $cliente = User::create([
                'name' => 'Jair Rodrigo Nolasco Irazabal',
                'email' => 'jair@worldtravel.com',
                'password' => bcrypt('password'),
                'rol' => 'cliente',
            ]);
        }

        $paqueteCusco = Paquete::where('destino', 'Cusco')->first() ?? Paquete::first();
        $paqueteCancun = Paquete::where('destino', 'Cancún')->first() ?? Paquete::skip(1)->first();
        $paqueteRoma = Paquete::where('destino', 'Roma')->first() ?? Paquete::skip(2)->first();
        $paqueteTokio = Paquete::where('destino', 'Tokio')->first() ?? Paquete::skip(3)->first();

        if (!$paqueteCusco) {
            return;
        }

        // 1. Reserva confirmada - Roma
        if ($paqueteRoma) {
            $r1 = Reserva::create([
                'user_id' => $cliente->id,
                'paquete_id' => $paqueteRoma->id,
                'fecha_viaje' => now()->addDays(15)->toDateString(),
                'num_pasajeros' => 1,
                'total' => 1230.00,
                'estado' => 'confirmada',
            ]);

            ReservaPasajero::create([
                'reserva_id' => $r1->id,
                'nombre' => $cliente->name,
                'documento' => '72349182',
            ]);
        }

        // 2. Reserva confirmada - Tokio
        if ($paqueteTokio) {
            $r2 = Reserva::create([
                'user_id' => $cliente->id,
                'paquete_id' => $paqueteTokio->id,
                'fecha_viaje' => now()->addDays(30)->toDateString(),
                'num_pasajeros' => 2,
                'total' => 2590.00,
                'estado' => 'confirmada',
            ]);

            ReservaPasajero::create([
                'reserva_id' => $r2->id,
                'nombre' => $cliente->name,
                'documento' => '72349182',
            ]);
            ReservaPasajero::create([
                'reserva_id' => $r2->id,
                'nombre' => 'Prashant Venkataraman',
                'documento' => 'P9823412',
            ]);
        }

        // 3. Reserva pendiente - Cusco
        $r3 = Reserva::create([
            'user_id' => $cliente->id,
            'paquete_id' => $paqueteCusco->id,
            'fecha_viaje' => now()->addDays(40)->toDateString(),
            'num_pasajeros' => 1,
            'total' => 519.00,
            'estado' => 'pendiente',
        ]);

        ReservaPasajero::create([
            'reserva_id' => $r3->id,
            'nombre' => $cliente->name,
            'documento' => '72349182',
        ]);

        // 4. Reserva confirmada familiar - Cancún
        if ($paqueteCancun) {
            $r4 = Reserva::create([
                'user_id' => $cliente->id,
                'paquete_id' => $paqueteCancun->id,
                'fecha_viaje' => now()->addDays(60)->toDateString(),
                'num_pasajeros' => 3,
                'total' => 2480.00,
                'estado' => 'confirmada',
            ]);

            ReservaPasajero::create([
                'reserva_id' => $r4->id,
                'nombre' => $cliente->name,
                'documento' => '72349182',
            ]);
            ReservaPasajero::create([
                'reserva_id' => $r4->id,
                'nombre' => 'Ravi Shankar',
                'documento' => 'P4561234',
            ]);
            ReservaPasajero::create([
                'reserva_id' => $r4->id,
                'nombre' => 'Lucía Mendoza',
                'documento' => '45678912',
            ]);
        }
    }
}

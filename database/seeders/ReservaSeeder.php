<?php

namespace Database\Seeders;

use App\Models\Alojamiento;
use App\Models\Oferta;
use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\ReservaPasajero;
use App\Models\User;
use App\Models\Vuelo;
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

        $vuelo = Vuelo::with(['origen', 'destino'])->first();
        $alojamiento = Alojamiento::where('activo', true)->first();
        $paqueteCusco = Paquete::where('destino', 'Cusco')->first() ?? Paquete::first();
        $ofertaBali = Oferta::with('paquete')->whereHas('paquete', fn ($q) => $q->where('destino', 'Bali'))->first() ?? Oferta::first();

        // 1. Categoría VUELOS (con 3 pasajeros y asientos 10A, 10B, 10C)
        $vueloCodigo = $vuelo?->codigo_vuelo ?? 'WT-8183';
        $vueloOrigen = $vuelo?->origen?->nombre ?? 'Lima';
        $vueloDestino = $vuelo?->destino?->nombre ?? 'Buenos Aires';
        $r1 = Reserva::updateOrCreate(
            ['id' => 1],
            [
                'user_id' => $cliente->id,
                'tipo_servicio' => 'vuelo',
                'vuelo_id' => $vuelo?->id,
                'paquete_id' => null,
                'alojamiento_id' => null,
                'oferta_id' => null,
                'fecha_viaje' => now()->addDays(14)->toDateString(),
                'moneda' => 'USD',
                'cotizacion' => 250.00,
                'num_pasajeros' => 3,
                'total' => 750.00,
                'asientos' => '10A, 10B, 10C',
                'detalle_servicio' => "{$vueloCodigo} · {$vueloOrigen} → {$vueloDestino}",
                'detalle_snapshot' => [
                    'codigo_vuelo' => $vueloCodigo,
                    'origen' => $vueloOrigen,
                    'destino' => $vueloDestino,
                    'asientos' => '10A, 10B, 10C',
                ],
                'estado' => 'confirmada',
            ]
        );

        ReservaPasajero::updateOrCreate(
            ['reserva_id' => $r1->id, 'nombre' => $cliente->name],
            ['documento' => '72349182', 'asiento' => '10A']
        );
        ReservaPasajero::updateOrCreate(
            ['reserva_id' => $r1->id, 'nombre' => 'Pasajero acompañante 2 ('.$cliente->name.')'],
            ['documento' => '48192834', 'asiento' => '10B']
        );
        ReservaPasajero::updateOrCreate(
            ['reserva_id' => $r1->id, 'nombre' => 'Pasajero acompañante 3 ('.$cliente->name.')'],
            ['documento' => '49102938', 'asiento' => '10C']
        );

        // 2. Categoría ALOJAMIENTOS (Hotel con 2 huéspedes y fechas reales)
        $hotelNombre = $alojamiento?->nombre ?? 'Miraflores Park, Lima';
        $hotelUbicacion = $alojamiento?->ubicacion ?? 'Lima, Perú';
        $r2 = Reserva::updateOrCreate(
            ['id' => 2],
            [
                'user_id' => $cliente->id,
                'tipo_servicio' => 'alojamiento',
                'alojamiento_id' => $alojamiento?->id,
                'vuelo_id' => null,
                'paquete_id' => null,
                'oferta_id' => null,
                'fecha_viaje' => now()->addDays(20)->toDateString(),
                'fecha_entrada' => now()->addDays(20)->toDateString(),
                'fecha_salida' => now()->addDays(23)->toDateString(),
                'noches' => 3,
                'moneda' => 'USD',
                'cotizacion' => 140.00,
                'num_pasajeros' => 2,
                'total' => 420.00,
                'asientos' => null,
                'detalle_servicio' => "{$hotelNombre} · {$hotelUbicacion}",
                'detalle_snapshot' => [
                    'nombre' => $hotelNombre,
                    'ubicacion' => $hotelUbicacion,
                    'noches' => 3,
                ],
                'estado' => 'confirmada',
            ]
        );

        ReservaPasajero::updateOrCreate(
            ['reserva_id' => $r2->id, 'nombre' => $cliente->name],
            ['documento' => '72349182', 'asiento' => null]
        );
        ReservaPasajero::updateOrCreate(
            ['reserva_id' => $r2->id, 'nombre' => 'Pasajero acompañante 2 ('.$cliente->name.')'],
            ['documento' => '48192834', 'asiento' => null]
        );

        // 3. Categoría PAQUETES (Cusco con vuelo incluido y 2 asientos 14A, 14B)
        if ($paqueteCusco) {
            $r3 = Reserva::updateOrCreate(
                ['id' => 3],
                [
                    'user_id' => $cliente->id,
                    'tipo_servicio' => 'paquete',
                    'paquete_id' => $paqueteCusco->id,
                    'vuelo_id' => null,
                    'alojamiento_id' => null,
                    'oferta_id' => null,
                    'fecha_viaje' => now()->addDays(35)->toDateString(),
                    'moneda' => 'USD',
                    'cotizacion' => (float) $paqueteCusco->precio,
                    'num_pasajeros' => 2,
                    'total' => (float) $paqueteCusco->precio * 2,
                    'asientos' => '14A, 14B',
                    'detalle_servicio' => $paqueteCusco->titulo,
                    'detalle_snapshot' => [
                        'titulo' => $paqueteCusco->titulo,
                        'destino' => $paqueteCusco->destino,
                        'pais' => $paqueteCusco->pais,
                    ],
                    'estado' => 'confirmada',
                ]
            );

            ReservaPasajero::updateOrCreate(
                ['reserva_id' => $r3->id, 'nombre' => $cliente->name],
                ['documento' => '72349182', 'asiento' => '14A']
            );
            ReservaPasajero::updateOrCreate(
                ['reserva_id' => $r3->id, 'nombre' => 'Pasajero acompañante 2 ('.$cliente->name.')'],
                ['documento' => '48192834', 'asiento' => '14B']
            );
        }

        // 4. Categoría OFERTAS (Bali con descuento del 22%, 2 pasajeros y asientos 18C, 18D)
        if ($ofertaBali) {
            $precioBase = (float) ($ofertaBali->paquete?->precio ?? 2080);
            $precioFinal = round($precioBase * (1 - $ofertaBali->descuento_porcentaje / 100), 2);
            $r4 = Reserva::updateOrCreate(
                ['id' => 4],
                [
                    'user_id' => $cliente->id,
                    'tipo_servicio' => 'oferta',
                    'oferta_id' => $ofertaBali->id,
                    'paquete_id' => null,
                    'vuelo_id' => null,
                    'alojamiento_id' => null,
                    'fecha_viaje' => now()->addDays(45)->toDateString(),
                    'moneda' => 'USD',
                    'cotizacion' => $precioFinal,
                    'num_pasajeros' => 2,
                    'total' => $precioFinal * 2,
                    'asientos' => '18C, 18D',
                    'detalle_servicio' => $ofertaBali->titulo,
                    'detalle_snapshot' => [
                        'titulo' => $ofertaBali->titulo,
                        'descuento' => $ofertaBali->descuento_porcentaje,
                        'destino' => $ofertaBali->paquete?->destino ?? 'Bali',
                    ],
                    'estado' => 'pendiente',
                ]
            );

            ReservaPasajero::updateOrCreate(
                ['reserva_id' => $r4->id, 'nombre' => $cliente->name],
                ['documento' => '72349182', 'asiento' => '18C']
            );
            ReservaPasajero::updateOrCreate(
                ['reserva_id' => $r4->id, 'nombre' => 'Pasajero acompañante 2 ('.$cliente->name.')'],
                ['documento' => '48192834', 'asiento' => '18D']
            );
        }

        // Mantener las 4 reservas activas de muestra en la base de datos
        $idsValidos = [1, 2, 3, 4];
        if (DB::getSchemaBuilder()->hasTable('pagos')) {
            DB::table('pagos')->whereNotIn('reserva_id', $idsValidos)->delete();
        }
        ReservaPasajero::whereNotIn('reserva_id', $idsValidos)->delete();
        Reserva::whereNotIn('id', $idsValidos)->delete();
    }
}

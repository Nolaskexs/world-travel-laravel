<?php

namespace Database\Seeders;

use App\Models\Destino;
use App\Models\Vuelo;
use App\Models\VueloEscala;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VueloSeeder extends Seeder
{
    public function run(): void
    {
        $fuk = Destino::where('codigo_iata', 'FUK')->orWhere('nombre', 'Fukuoka')->first();
        $lim = Destino::where('codigo_iata', 'LIM')->orWhere('nombre', 'Lima')->first();
        $hnd = Destino::where('codigo_iata', 'HND')->orWhere('nombre', 'Tokio')->first();
        $cdg = Destino::where('codigo_iata', 'CDG')->orWhere('nombre', 'París')->first();
        $cuz = Destino::where('codigo_iata', 'CUZ')->orWhere('nombre', 'Cusco')->first();
        $mad = Destino::where('codigo_iata', 'MAD')->orWhere('nombre', 'Madrid')->first();

        if (!$fuk || !$lim) {
            return;
        }

        $baseFecha = Carbon::create(2026, 11, 15, 23, 45, 0);

        // Vuelo 1: El más barato (FUK -> LIM con LATAM)
        $vuelo1 = Vuelo::updateOrCreate(
            ['codigo_vuelo' => 'LA-565'],
            [
                'origen_id' => $fuk->id,
                'destino_id' => $lim->id,
                'aerolinea' => 'LATAM Airlines',
                'fecha_salida' => $baseFecha->copy(),
                'fecha_llegada' => $baseFecha->copy()->addMinutes(3105),
                'duracion_minutos' => 3105,
                'dias_diferencia' => 2,
                'cantidad_escalas' => 2,
                'incluye_articulo_personal' => true,
                'incluye_equipaje_mano' => false,
                'incluye_equipaje_bodega' => true,
                'moneda' => 'USD',
                'precio_adulto' => 2150.00,
                'precio_menor' => 1720.00,
                'precio_infante' => 215.00,
                'impuestos_tasas' => 1210.00,
                'precio_final' => 3360.00,
                'asientos_disponibles' => 38,
                'etiqueta' => 'el_mas_barato',
                'activo' => true,
            ]
        );

        if ($hnd) {
            VueloEscala::updateOrCreate(
                ['vuelo_id' => $vuelo1->id, 'orden' => 1],
                [
                    'numero_vuelo' => 'LA-565',
                    'aerolinea_operadora' => 'LATAM Airlines',
                    'origen_destino_id' => $fuk->id,
                    'llegada_destino_id' => $hnd->id,
                    'hora_salida' => $baseFecha->copy(),
                    'hora_llegada' => $baseFecha->copy()->addMinutes(855),
                    'duracion_tramo_minutos' => 855,
                    'tiempo_espera_minutos' => 270,
                    'cambio_avion' => true,
                ]
            );

            VueloEscala::updateOrCreate(
                ['vuelo_id' => $vuelo1->id, 'orden' => 2],
                [
                    'numero_vuelo' => 'LA-594',
                    'aerolinea_operadora' => 'LATAM Airlines',
                    'origen_destino_id' => $hnd->id,
                    'llegada_destino_id' => $lim->id,
                    'hora_salida' => $baseFecha->copy()->addMinutes(1125),
                    'hora_llegada' => $baseFecha->copy()->addMinutes(3105),
                    'duracion_tramo_minutos' => 1980,
                    'tiempo_espera_minutos' => null,
                    'cambio_avion' => false,
                ]
            );
        }

        // Vuelo 2: Mejor opción (FUK -> LIM con KLM)
        $salidaKlm = Carbon::create(2026, 11, 15, 13, 30, 0);
        $vuelo2 = Vuelo::updateOrCreate(
            ['codigo_vuelo' => 'KL-861'],
            [
                'origen_id' => $fuk->id,
                'destino_id' => $lim->id,
                'aerolinea' => 'KLM',
                'fecha_salida' => $salidaKlm,
                'fecha_llegada' => $salidaKlm->copy()->addMinutes(2950),
                'duracion_minutos' => 2950,
                'dias_diferencia' => 2,
                'cantidad_escalas' => 1,
                'incluye_articulo_personal' => true,
                'incluye_equipaje_mano' => true,
                'incluye_equipaje_bodega' => true,
                'moneda' => 'USD',
                'precio_adulto' => 2343.00,
                'precio_menor' => 1870.00,
                'precio_infante' => 230.00,
                'impuestos_tasas' => 1389.00,
                'precio_final' => 3732.00,
                'asientos_disponibles' => 25,
                'etiqueta' => 'mejor_opcion',
                'activo' => true,
            ]
        );

        if ($cdg) {
            VueloEscala::updateOrCreate(
                ['vuelo_id' => $vuelo2->id, 'orden' => 1],
                [
                    'numero_vuelo' => 'KL-861',
                    'aerolinea_operadora' => 'KLM',
                    'origen_destino_id' => $fuk->id,
                    'llegada_destino_id' => $cdg->id,
                    'hora_salida' => $salidaKlm,
                    'hora_llegada' => $salidaKlm->copy()->addMinutes(920),
                    'duracion_tramo_minutos' => 920,
                    'tiempo_espera_minutos' => 240,
                    'cambio_avion' => true,
                ]
            );
            VueloEscala::updateOrCreate(
                ['vuelo_id' => $vuelo2->id, 'orden' => 2],
                [
                    'numero_vuelo' => 'KL-743',
                    'aerolinea_operadora' => 'KLM',
                    'origen_destino_id' => $cdg->id,
                    'llegada_destino_id' => $lim->id,
                    'hora_salida' => $salidaKlm->copy()->addMinutes(1160),
                    'hora_llegada' => $salidaKlm->copy()->addMinutes(2950),
                    'duracion_tramo_minutos' => 1790,
                    'tiempo_espera_minutos' => null,
                    'cambio_avion' => false,
                ]
            );
        }

        // Vuelo 3: Menor duración (FUK -> LIM con United Airlines)
        $salidaUa = Carbon::create(2026, 11, 15, 11, 30, 0);
        $vuelo3 = Vuelo::updateOrCreate(
            ['codigo_vuelo' => 'UA-838'],
            [
                'origen_id' => $fuk->id,
                'destino_id' => $lim->id,
                'aerolinea' => 'United Airlines',
                'fecha_salida' => $salidaUa,
                'fecha_llegada' => $salidaUa->copy()->addMinutes(2420),
                'duracion_minutos' => 2420,
                'dias_diferencia' => 1,
                'cantidad_escalas' => 1,
                'incluye_articulo_personal' => true,
                'incluye_equipaje_mano' => true,
                'incluye_equipaje_bodega' => true,
                'moneda' => 'USD',
                'precio_adulto' => 3390.00,
                'precio_menor' => 2710.00,
                'precio_infante' => 330.00,
                'impuestos_tasas' => 1680.00,
                'precio_final' => 5070.00,
                'asientos_disponibles' => 14,
                'etiqueta' => 'menor_duracion',
                'activo' => true,
            ]
        );

        // Vuelo 4: Air France
        $salidaAf = Carbon::create(2026, 11, 15, 10, 0, 0);
        Vuelo::updateOrCreate(
            ['codigo_vuelo' => 'AF-275'],
            [
                'origen_id' => $fuk->id,
                'destino_id' => $lim->id,
                'aerolinea' => 'Air France',
                'fecha_salida' => $salidaAf,
                'fecha_llegada' => $salidaAf->copy()->addMinutes(2695),
                'duracion_minutos' => 2695,
                'dias_diferencia' => 2,
                'cantidad_escalas' => 2,
                'incluye_articulo_personal' => true,
                'incluye_equipaje_mano' => true,
                'incluye_equipaje_bodega' => true,
                'moneda' => 'USD',
                'precio_adulto' => 2269.00,
                'precio_menor' => 1815.00,
                'precio_infante' => 220.00,
                'impuestos_tasas' => 1407.00,
                'precio_final' => 3676.00,
                'asientos_disponibles' => 20,
                'etiqueta' => 'ninguna',
                'activo' => true,
            ]
        );

        // Vuelo 5: All Nippon Airways
        $salidaAna = Carbon::create(2026, 11, 15, 21, 30, 0);
        Vuelo::updateOrCreate(
            ['codigo_vuelo' => 'NH-214'],
            [
                'origen_id' => $fuk->id,
                'destino_id' => $lim->id,
                'aerolinea' => 'All Nippon Airways',
                'fecha_salida' => $salidaAna,
                'fecha_llegada' => $salidaAna->copy()->addMinutes(2900),
                'duracion_minutos' => 2900,
                'dias_diferencia' => 2,
                'cantidad_escalas' => 1,
                'incluye_articulo_personal' => true,
                'incluye_equipaje_mano' => true,
                'incluye_equipaje_bodega' => false,
                'moneda' => 'USD',
                'precio_adulto' => 2412.00,
                'precio_menor' => 1930.00,
                'precio_infante' => 240.00,
                'impuestos_tasas' => 1320.00,
                'precio_final' => 3732.00,
                'asientos_disponibles' => 30,
                'etiqueta' => 'ninguna',
                'activo' => true,
            ]
        );

        // Vuelos adicionales entre destinos populares
        if ($lim && $cuz) {
            Vuelo::updateOrCreate(
                ['codigo_vuelo' => 'LA-2011'],
                [
                    'origen_id' => $lim->id,
                    'destino_id' => $cuz->id,
                    'aerolinea' => 'LATAM Airlines',
                    'fecha_salida' => Carbon::create(2026, 11, 16, 7, 0, 0),
                    'fecha_llegada' => Carbon::create(2026, 11, 16, 8, 20, 0),
                    'duracion_minutos' => 80,
                    'dias_diferencia' => 0,
                    'cantidad_escalas' => 0,
                    'incluye_articulo_personal' => true,
                    'incluye_equipaje_mano' => true,
                    'incluye_equipaje_bodega' => true,
                    'moneda' => 'USD',
                    'precio_adulto' => 85.00,
                    'precio_menor' => 65.00,
                    'precio_infante' => 15.00,
                    'impuestos_tasas' => 25.00,
                    'precio_final' => 110.00,
                    'asientos_disponibles' => 50,
                    'etiqueta' => 'el_mas_barato',
                    'activo' => true,
                ]
            );
        }

        if ($mad && $lim) {
            Vuelo::updateOrCreate(
                ['codigo_vuelo' => 'IB-6651'],
                [
                    'origen_id' => $mad->id,
                    'destino_id' => $lim->id,
                    'aerolinea' => 'Iberia',
                    'fecha_salida' => Carbon::create(2026, 11, 18, 13, 0, 0),
                    'fecha_llegada' => Carbon::create(2026, 11, 18, 19, 15, 0),
                    'duracion_minutos' => 735,
                    'dias_diferencia' => 0,
                    'cantidad_escalas' => 0,
                    'incluye_articulo_personal' => true,
                    'incluye_equipaje_mano' => true,
                    'incluye_equipaje_bodega' => true,
                    'moneda' => 'USD',
                    'precio_adulto' => 650.00,
                    'precio_menor' => 520.00,
                    'precio_infante' => 65.00,
                    'impuestos_tasas' => 180.00,
                    'precio_final' => 830.00,
                    'asientos_disponibles' => 45,
                    'etiqueta' => 'el_mas_barato',
                    'activo' => true,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Reclamacion;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReclamacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cliente = User::where('rol', 'cliente')->first() ?? User::first();
        $admin = User::where('rol', 'admin')->first();

        $reclamaciones = [
            [
                'codigo' => 'REC-2026-0001',
                'user_id' => $cliente?->id,
                'tipo_persona' => 'natural',
                'nombre' => 'Carlos Mendoza Quispe',
                'tipo_documento' => 'DNI',
                'documento' => '45892134',
                'email' => 'carlos.mendoza@gmail.com',
                'telefono' => '984512345',
                'pais' => 'Perú',
                'tipo_bien' => 'servicio',
                'producto' => 'Vuelo Lima - Cancún (WT-420)',
                'reserva' => '1024',
                'moneda' => 'USD',
                'monto' => 120.00,
                'tipo' => 'reclamo',
                'motivo' => 'reclamo_vuelo',
                'detalle' => 'Mi vuelo presentó un retraso de más de 4 horas en la salida del aeropuerto Jorge Chávez sin previo aviso ni refrigerio proporcionado por el personal en puerta.',
                'pedido' => 'Solicito el reembolso del costo adicional de transporte nocturno y una compensación de acuerdo a los derechos del pasajero.',
                'formato_respuesta' => 'digital',
                'estado' => 'pendiente',
                'respuesta' => null,
                'respondido_por' => null,
                'fecha_respuesta' => null,
                'created_at' => now()->subDays(2),
            ],
            [
                'codigo' => 'REC-2026-0002',
                'user_id' => null,
                'tipo_persona' => 'natural',
                'nombre' => 'Mariana Torres Vega',
                'tipo_documento' => 'DNI',
                'documento' => '70123456',
                'email' => 'marianatorres@hotmail.com',
                'telefono' => '912345678',
                'pais' => 'Perú',
                'tipo_bien' => 'servicio',
                'producto' => 'Paquete Cusco Mágico (4D/3N)',
                'reserva' => '1058',
                'moneda' => 'PEN',
                'monto' => 350.00,
                'tipo' => 'reclamo',
                'motivo' => 'reembolso_cancelacion',
                'detalle' => 'Se solicitó la reprogramación del tour a Machu Picchu con 5 días de anticipación por motivos médicos documentados, pero la agencia indicó que no aplicaba cambio de fecha.',
                'pedido' => 'Emisión de una nota de crédito o reprogramación sin penalidad para viajar el próximo mes.',
                'formato_respuesta' => 'digital',
                'estado' => 'en_proceso',
                'respuesta' => 'Estimada Sra. Torres, su caso fue derivado al área de Operaciones Turísticas para coordinar la exoneración con el operador ferroviario.',
                'respondido_por' => $admin?->id,
                'fecha_respuesta' => now()->subDay(),
                'created_at' => now()->subDays(3),
            ],
            [
                'codigo' => 'REC-2026-0003',
                'user_id' => null,
                'tipo_persona' => 'juridica',
                'nombre' => 'Inversiones Turísticas Andinas S.A.C.',
                'tipo_documento' => 'RUC',
                'documento' => '20556789123',
                'email' => 'administracion@turismoandino.pe',
                'telefono' => '01-4458920',
                'pais' => 'Perú',
                'tipo_bien' => 'servicio',
                'producto' => 'Reserva Corporativa Vuelos Tokio WT-8183',
                'reserva' => '1080',
                'moneda' => 'USD',
                'monto' => 240.00,
                'tipo' => 'reclamo',
                'motivo' => 'facturacion_cobro',
                'detalle' => 'Se emitió una boleta en lugar de la factura electrónica solicitada con RUC para los tres ejecutivos de la delegación.',
                'pedido' => 'Anulación de la boleta electrónica y emisión de la factura correspondiente con el RUC de la empresa.',
                'formato_respuesta' => 'digital',
                'estado' => 'atendido',
                'respuesta' => 'Estimados, se procedió con la nota de crédito y la emisión de la Factura Electrónica F001-0004521 remitida a su correo.',
                'respondido_por' => $admin?->id,
                'fecha_respuesta' => now()->subHours(6),
                'created_at' => now()->subDays(5),
            ],
            [
                'codigo' => 'REC-2026-0004',
                'user_id' => $cliente?->id,
                'tipo_persona' => 'natural',
                'nombre' => 'Jorge Luis Paredes',
                'tipo_documento' => 'DNI',
                'documento' => '41852963',
                'email' => 'jorge.paredes@outlook.com',
                'telefono' => '998877665',
                'pais' => 'Perú',
                'tipo_bien' => 'servicio',
                'producto' => 'Atención al Cliente en Módulo',
                'reserva' => null,
                'moneda' => 'PEN',
                'monto' => null,
                'tipo' => 'queja',
                'motivo' => 'queja_atencion',
                'detalle' => 'El día de hoy me acerqué a la oficina para consultar sobre los asientos reservados y la persona encargada en ventanilla mostró desinterés y mala predisposición para resolver mis dudas.',
                'pedido' => 'Capacitación en atención al cliente y disculpas formales.',
                'formato_respuesta' => 'digital',
                'estado' => 'pendiente',
                'respuesta' => null,
                'respondido_por' => null,
                'fecha_respuesta' => null,
                'created_at' => now()->subHours(8),
            ],
        ];

        foreach ($reclamaciones as $rec) {
            Reclamacion::updateOrCreate(['codigo' => $rec['codigo']], $rec);
        }
    }
}

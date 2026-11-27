<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table): void {
            if (! Schema::hasColumn('reservas', 'tipo_servicio')) {
                $table->string('tipo_servicio', 30)->default('paquete')->after('paquete_id');
            }
            if (! Schema::hasColumn('reservas', 'vuelo_id')) {
                $table->foreignId('vuelo_id')->nullable()->constrained('vuelos')->nullOnDelete()->after('tipo_servicio');
            }
            if (! Schema::hasColumn('reservas', 'alojamiento_id')) {
                $table->foreignId('alojamiento_id')->nullable()->constrained('alojamientos')->nullOnDelete();
            }
            if (! Schema::hasColumn('reservas', 'oferta_id')) {
                $table->foreignId('oferta_id')->nullable()->constrained('ofertas')->nullOnDelete();
            }

            if (! Schema::hasColumn('reservas', 'fecha_entrada')) {
                $table->date('fecha_entrada')->nullable()->after('fecha_viaje');
            }
            if (! Schema::hasColumn('reservas', 'fecha_salida')) {
                $table->date('fecha_salida')->nullable()->after('fecha_entrada');
            }
            if (! Schema::hasColumn('reservas', 'noches')) {
                $table->unsignedSmallInteger('noches')->nullable()->after('fecha_salida');
            }
            if (! Schema::hasColumn('reservas', 'moneda')) {
                $table->string('moneda', 5)->default('USD')->after('noches');
            }
            if (! Schema::hasColumn('reservas', 'cotizacion')) {
                $table->decimal('cotizacion', 10, 2)->nullable()->after('moneda');
            }
            if (! Schema::hasColumn('reservas', 'detalle_servicio')) {
                $table->string('detalle_servicio', 255)->nullable()->after('total');
            }
            if (! Schema::hasColumn('reservas', 'detalle_snapshot')) {
                $table->json('detalle_snapshot')->nullable()->after('detalle_servicio');
            }
            if (! Schema::hasColumn('reservas', 'asientos')) {
                $table->string('asientos', 255)->nullable()->after('detalle_snapshot');
            }

            $table->foreignId('paquete_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table): void {
            $table->dropForeign(['vuelo_id']);
            $table->dropForeign(['alojamiento_id']);
            $table->dropForeign(['oferta_id']);
            $table->dropColumn([
                'tipo_servicio',
                'vuelo_id',
                'alojamiento_id',
                'oferta_id',
                'fecha_entrada',
                'fecha_salida',
                'noches',
                'moneda',
                'cotizacion',
                'detalle_servicio',
                'detalle_snapshot',
                'asientos',
            ]);
        });

        $paqueteId = DB::table('paquetes')->orderBy('id')->value('id');

        if ($paqueteId) {
            DB::table('reservas')->whereNull('paquete_id')->update(['paquete_id' => $paqueteId]);
        }

        Schema::table('reservas', function (Blueprint $table): void {
            $table->foreignId('paquete_id')->nullable(false)->change();
        });
    }
};

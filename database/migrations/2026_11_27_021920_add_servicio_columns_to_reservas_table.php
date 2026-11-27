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
            $table->string('tipo_servicio', 30)->default('paquete')->after('paquete_id');
            $table->foreignId('vuelo_id')->nullable()->constrained('vuelos')->nullOnDelete()->after('tipo_servicio');
            $table->foreignId('alojamiento_id')->nullable()->constrained('alojamientos')->nullOnDelete()->after('vuelo_id');
            $table->foreignId('oferta_id')->nullable()->constrained('ofertas')->nullOnDelete()->after('alojamiento_id');

            $table->date('fecha_entrada')->nullable()->after('fecha_viaje');
            $table->date('fecha_salida')->nullable()->after('fecha_entrada');
            $table->unsignedSmallInteger('noches')->nullable()->after('fecha_salida');
            $table->string('moneda', 5)->default('USD')->after('noches');
            $table->decimal('cotizacion', 10, 2)->nullable()->after('moneda');
            $table->string('detalle_servicio', 255)->nullable()->after('total');
            $table->json('detalle_snapshot')->nullable()->after('detalle_servicio');
            $table->string('asientos', 255)->nullable()->after('detalle_snapshot');

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

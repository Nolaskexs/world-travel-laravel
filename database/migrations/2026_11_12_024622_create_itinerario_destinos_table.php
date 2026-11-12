
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crear la tabla de destinos del itinerario.
     */
    public function up(): void
    {
        Schema::create('itinerario_destinos', function (Blueprint $table) {
            $table->id();

            // Relación con el itinerario principal
            $table->foreignId('itinerario_id')
                ->constrained('itinerarios')
                ->cascadeOnDelete();

            // Orden de visita
            $table->unsignedInteger('orden');

            // Ubicación internacional
            $table->string('pais', 120);
            $table->string('ciudad', 120);

            // Fechas de estancia
            $table->date('fecha_llegada');
            $table->date('fecha_salida');

            // Presupuesto estimado en dólares
            $table->decimal('costo_estimado_usd', 12, 2)
                ->default(0);

            // Observaciones del viajero
            $table->text('notas')->nullable();

            // Evitar órdenes duplicados en un mismo itinerario
            $table->unique(['itinerario_id', 'orden']);

            $table->timestamps();
        });
    }

    /**
     * Eliminar la tabla al revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('itinerario_destinos');
    }
};

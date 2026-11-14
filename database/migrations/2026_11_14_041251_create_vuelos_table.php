<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vuelos', function (Blueprint $table) {
            $table->id();

            // Relaciones con tu tabla destinos (Origen y Destino)
            $table->foreignId('origen_id')->constrained('destinos')->cascadeOnDelete();
            $table->foreignId('destino_id')->constrained('destinos')->cascadeOnDelete();

            // Datos generales de la aerolínea y vuelo principal
            $table->string('codigo_vuelo', 20); // Ej: LA-565
            $table->string('aerolinea', 80)->default('LATAM Airlines');

            // Fechas y horas (Ida)
            $table->dateTime('fecha_salida');
            $table->dateTime('fecha_llegada');
            $table->unsignedInteger('duracion_minutos'); // Ej: 3105 (para calcular "51h 45m" y ordenar por "Menor duración")
            $table->unsignedTinyInteger('dias_diferencia')->default(0); // Para mostrar el "+2" o "+3" en la hora de llegada

            // Filtros de Escalas (0 = Directo, 1 = 1 escala, 2+ = 2 o más escalas)
            $table->unsignedTinyInteger('cantidad_escalas')->default(0)->index();

            // Filtros de Equipaje (Checkboxes laterales e íconos en la tarjeta)
            $table->boolean('incluye_articulo_personal')->default(true);
            $table->boolean('incluye_equipaje_mano')->default(true);
            $table->boolean('incluye_equipaje_bodega')->default(false);

            // Precios y Moneda (Cuadro derecho de la tarjeta)
            $table->string('moneda', 3)->default('USD'); // USD o PEN
            $table->decimal('precio_adulto', 10, 2);      // Ej: 2150.00
            $table->decimal('precio_menor', 10, 2)->nullable();
            $table->decimal('precio_infante', 10, 2)->default(0);
            $table->decimal('impuestos_tasas', 10, 2);    // Ej: 1210.00
            $table->decimal('precio_final', 10, 2)->index(); // Ej: 3360.00 (Para ordenar por "El más barato")

            // Disponibilidad y etiquetas
            $table->unsignedSmallInteger('asientos_disponibles')->default(45);
            $table->enum('etiqueta', ['ninguna', 'el_mas_barato', 'mejor_opcion', 'menor_duracion'])->default('ninguna');
            $table->boolean('activo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vuelos');
    }
};

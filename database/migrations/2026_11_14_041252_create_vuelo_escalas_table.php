<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vuelo_escalas', function (Blueprint $table) {
            $table->id();

            // A qué vuelo pertenece este tramo
            $table->foreignId('vuelo_id')->constrained('vuelos')->cascadeOnDelete();

            // Orden del tramo (1 = Primer avión, 2 = Segundo avión, etc.)
            $table->unsignedTinyInteger('orden')->default(1);

            // Datos del avión en este tramo
            $table->string('numero_vuelo', 20); // Ej: "565" o "594"
            $table->string('aerolinea_operadora', 80)->default('LATAM Airlines');

            // Aeropuerto de salida y llegada del tramo
            $table->foreignId('origen_destino_id')->constrained('destinos')->cascadeOnDelete();
            $table->foreignId('llegada_destino_id')->constrained('destinos')->cascadeOnDelete();

            // Horarios del tramo
            $table->dateTime('hora_salida');  // Ej: 11:45 PM
            $table->dateTime('hora_llegada'); // Ej: 02:00 PM (Llega el lun 16 nov)
            $table->unsignedInteger('duracion_tramo_minutos'); // Ej: 855 (14h 15m)

            // Información de la espera antes del siguiente avión (null si es el último tramo)
            $table->unsignedInteger('tiempo_espera_minutos')->nullable(); // Ej: 270 (4h 30m)
            $table->boolean('cambio_avion')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vuelo_escalas');
    }
};

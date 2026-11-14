<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destino_id')->nullable()->constrained('destinos')->nullOnDelete();
            $table->string('titulo', 150);
            $table->string('destino', 100);
            $table->string('pais', 100)->default('Perú');
            $table->text('descripcion');
            $table->unsignedTinyInteger('duracion_dias');
            $table->decimal('precio', 10, 2);
            $table->string('imagen_url', 500)->nullable();
            $table->boolean('incluye_vuelo')->default(false);
            $table->boolean('incluye_hotel')->default(true);
            $table->boolean('incluye_tour')->default(true);
            $table->boolean('destacado')->default(false);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paquetes');
    }
};

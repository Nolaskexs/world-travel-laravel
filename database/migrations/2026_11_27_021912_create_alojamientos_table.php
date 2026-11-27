<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alojamientos', function (Blueprint $table): void {
            $table->id();
            $table->string('codigo', 80)->unique();
            $table->string('nombre', 150);
            $table->string('tipo', 100);
            $table->string('categoria', 30)->index();
            $table->string('ubicacion', 180);
            $table->string('habitacion', 120)->nullable();
            $table->text('descripcion');
            $table->string('imagen', 500);
            $table->string('imagen_tarjeta', 500)->nullable();
            $table->decimal('precio', 10, 2);
            $table->decimal('precio_anterior', 10, 2)->nullable();
            $table->string('moneda_simbolo', 5)->default('US$');
            $table->decimal('puntuacion', 3, 1)->nullable();
            $table->unsignedTinyInteger('estrellas')->nullable();
            $table->json('servicios')->nullable();
            $table->string('servicios_resumen', 255)->nullable();
            $table->string('palabras_clave', 255)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alojamientos');
    }
};

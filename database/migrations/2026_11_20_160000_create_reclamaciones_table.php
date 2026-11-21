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
        Schema::create('reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique(); // Ej: REC-2026-0001
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // 1. Identificación del Consumidor
            $table->enum('tipo_persona', ['natural', 'juridica'])->default('natural');
            $table->string('nombre', 150);
            $table->string('tipo_documento', 20)->default('DNI'); // DNI, RUC, CE, Pasaporte
            $table->string('documento', 30);
            $table->string('email', 150);
            $table->string('telefono', 40)->nullable();
            $table->string('pais', 80)->default('Perú');

            // 2. Identificación del Bien Contratado
            $table->enum('tipo_bien', ['producto', 'servicio'])->default('servicio');
            $table->string('producto', 180);
            $table->string('reserva', 50)->nullable();
            $table->string('moneda', 3)->default('PEN'); // PEN o USD
            $table->decimal('monto', 10, 2)->nullable();

            // 3. Detalle de la Reclamación
            $table->enum('tipo', ['reclamo', 'queja'])->default('reclamo');
            $table->string('motivo', 100); // reclamo_vuelo, queja_atencion, facturacion_cobro, reembolso_cancelacion, etc.
            $table->text('detalle');
            $table->text('pedido');

            // 4. Formato de Respuesta preferido por el consumidor
            $table->enum('formato_respuesta', ['digital', 'fisico'])->default('digital');

            // 5. Gestión Administrativa / Respuesta del Proveedor
            $table->enum('estado', ['pendiente', 'en_proceso', 'atendido', 'archivado'])->default('pendiente')->index();
            $table->text('respuesta')->nullable();
            $table->foreignId('respondido_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_respuesta')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reclamaciones');
    }
};

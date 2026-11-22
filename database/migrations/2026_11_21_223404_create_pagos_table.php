
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            // Reserva asociada al pago
            $table->foreignId('reserva_id')
                ->constrained('reservas')
                ->restrictOnDelete();

            // Proveedor y método
            $table->string('proveedor', 30)->default('culqi');
            $table->string('metodo', 30)->default('tarjeta');

            // Importe en la moneda original
            $table->decimal('monto_original', 12, 2);
            $table->string('moneda_original', 3);

            // Importe que se enviará a la pasarela
            $table->decimal('monto', 12, 2);
            $table->string('moneda', 3);

            // Conversión de moneda, cuando corresponda
            $table->decimal('tipo_cambio', 16, 8)
                ->nullable();

            // pendiente, procesando, aprobado o rechazado
            $table->string('estado', 20)
                ->default('pendiente');

            // Identificadores del proveedor
            $table->string('culqi_cargo_id')
                ->nullable()
                ->unique();

            $table->string('culqi_orden_id')
                ->nullable()
                ->unique();

            // Protección contra operaciones duplicadas
            $table->uuid('clave_idempotencia')
                ->nullable()
                ->unique();

            $table->timestamp('verificado_en')
                ->nullable();

            $table->timestamps();

            $table->index(['reserva_id', 'estado']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

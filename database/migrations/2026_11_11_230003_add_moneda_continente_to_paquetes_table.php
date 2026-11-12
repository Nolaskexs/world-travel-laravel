<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
            $table->string('continente', 30)->nullable()->after('pais');
            $table->char('moneda', 3)->default('USD')->after('precio');
            $table->index(['activo', 'continente']);
        });
    }

    public function down(): void
    {
        Schema::table('paquetes', function (Blueprint $table) {
            $table->dropIndex(['activo', 'continente']);
            $table->dropColumn(['continente', 'moneda']);
        });
    }
};

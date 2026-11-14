<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('destinos', function (Blueprint $table) {
            if (!Schema::hasColumn('destinos', 'codigo_iata')) {
                $table->string('codigo_iata', 3)->nullable()->after('id')->index(); // Ej: LIM, CUZ, FCO, HND, FUK
            }
            if (!Schema::hasColumn('destinos', 'aeropuerto')) {
                $table->string('aeropuerto', 120)->nullable()->after('codigo_iata'); // Ej: Tokio - Haneda
            }
        });
    }

    public function down(): void
    {
        Schema::table('destinos', function (Blueprint $table) {
            $table->dropColumn(['codigo_iata', 'aeropuerto']);
        });
    }
};

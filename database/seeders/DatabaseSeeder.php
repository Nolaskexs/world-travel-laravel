<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@worldtravel.com'],
            ['name' => 'Administrador', 'password' => Hash::make('Admin12345'), 'rol' => 'admin']
        );

        User::firstOrCreate(
            ['email' => 'cliente@worldtravel.com'],
            ['name' => 'Cliente Demo', 'password' => Hash::make('Cliente12345'), 'rol' => 'cliente']
        );

        $this->call([
            PaquetesOfertasSeeder::class,
            DestinosSeeder::class, // siempre DESPUÉS de PaquetesOfertasSeeder
            AlojamientoSeeder::class,
            ReservaSeeder::class,
            VueloSeeder::class,
            ReclamacionSeeder::class,
        ]);
    }
}

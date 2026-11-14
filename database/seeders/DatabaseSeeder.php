<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
            ReservaSeeder::class,
            VueloSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Destino;
use App\Models\Paquete;
use App\Models\Oferta;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Usuarios (Admin y Cliente)
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@worldtravel.com',
            'password' => bcrypt('password'),
            'rol' => 'admin'
        ]);

        User::create([
            'name' => 'Cliente Viajero',
            'email' => 'cliente@worldtravel.com',
            'password' => bcrypt('password'),
            'rol' => 'cliente'
        ]);

        // 2. Crear 5 Destinos
        $cusco = Destino::create([
            'nombre' => 'Cusco', 'pais' => 'Perú', 'descripcion' => 'Capital histórica del Imperio Inca.', 'imagen' => 'cusco.jpg'
        ]);
        $cancun = Destino::create([
            'nombre' => 'Cancún', 'pais' => 'México', 'descripcion' => 'Playas paradisíacas de arena blanca.', 'imagen' => 'cancun.jpg'
        ]);
        $roma = Destino::create([
            'nombre' => 'Roma', 'pais' => 'Italia', 'descripcion' => 'La Ciudad Eterna, llena de historia y cultura.', 'imagen' => 'roma.jpg'
        ]);
        $tokio = Destino::create([
            'nombre' => 'Tokio', 'pais' => 'Japón', 'descripcion' => 'Metrópolis vibrante que mezcla lo tradicional y lo moderno.', 'imagen' => 'tokio.jpg'
        ]);
        $ny = Destino::create([
            'nombre' => 'Nueva York', 'pais' => 'Estados Unidos', 'descripcion' => 'La ciudad que nunca duerme.', 'imagen' => 'ny.jpg'
        ]);

        // 3. Crear 6 Paquetes Turísticos
        $paquete1 = Paquete::create([
            'destino_id' => $cusco->id, 'nombre' => 'Aventura en Machu Picchu', 'descripcion' => '5 días recorriendo el Valle Sagrado.', 'dias' => 5, 'precio' => 450.00, 'cupos' => 20
        ]);
        $paquete2 = Paquete::create([
            'destino_id' => $cancun->id, 'nombre' => 'Relax en el Caribe', 'descripcion' => 'Todo incluido en resort 5 estrellas.', 'dias' => 4, 'precio' => 850.00, 'cupos' => 15
        ]);
        $paquete3 = Paquete::create([
            'destino_id' => $roma->id, 'nombre' => 'Tour del Coliseo y Vaticano', 'descripcion' => 'Descubre los secretos de la antigua Roma.', 'dias' => 6, 'precio' => 1200.00, 'cupos' => 10
        ]);
        $paquete4 = Paquete::create([
            'destino_id' => $tokio->id, 'nombre' => 'Japón Express', 'descripcion' => 'Recorrido en tren bala por Tokio y Kioto.', 'dias' => 7, 'precio' => 2100.00, 'cupos' => 12
        ]);
        $paquete5 = Paquete::create([
            'destino_id' => $ny->id, 'nombre' => 'Escapada a Manhattan', 'descripcion' => 'Fin de semana de compras y espectáculos.', 'dias' => 3, 'precio' => 950.00, 'cupos' => 8
        ]);
        $paquete6 = Paquete::create([
            'destino_id' => $cusco->id, 'nombre' => 'Camino Inca Clásico', 'descripcion' => 'Trekking de 4 días hacia la ciudadela.', 'dias' => 4, 'precio' => 600.00, 'cupos' => 10
        ]);

        // 4. Crear 3 Ofertas
        Oferta::create([
            'paquete_id' => $paquete1->id, 'descuento' => 15.00, 'fecha_inicio' => now(), 'fecha_fin' => now()->addDays(30), 'activa' => true
        ]);
        Oferta::create([
            'paquete_id' => $paquete4->id, 'descuento' => 10.00, 'fecha_inicio' => now(), 'fecha_fin' => now()->addDays(15), 'activa' => true
        ]);
        Oferta::create([
            'paquete_id' => $paquete2->id, 'descuento' => 20.00, 'fecha_inicio' => now(), 'fecha_fin' => now()->addDays(7), 'activa' => true
        ]);
    }
}
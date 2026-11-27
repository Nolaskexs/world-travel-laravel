<?php

namespace Tests\Feature;

use App\Models\Paquete;
use Database\Seeders\AlojamientoSeeder;
use Database\Seeders\PaquetesOfertasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogosHomeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PaquetesOfertasSeeder::class);
        $this->seed(AlojamientoSeeder::class);
    }

    public function test_pagina_inicio_carga_correctamente(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Paquetes turísticos con los mejores precios');
        $response->assertSee('Hospedajes para disfrutar');
    }

    public function test_detalles_de_paquetes_de_la_home_cargan_correctamente(): void
    {
        $roma = Paquete::where('destino', 'Roma')->first();
        $this->assertNotNull($roma);
        $responseRoma = $this->get(route('paquetes.detalle', $roma));
        $responseRoma->assertStatus(200);
        $responseRoma->assertSee('Roma');
        $responseRoma->assertSee('Reservar este paquete');

        $tokio = Paquete::where('destino', 'Tokio')->first();
        $this->assertNotNull($tokio);
        $responseTokio = $this->get(route('paquetes.detalle', $tokio));
        $responseTokio->assertStatus(200);
        $responseTokio->assertSee('Tokio');

        $india = Paquete::where('pais', 'India')->first();
        $this->assertNotNull($india);
        $responseIndia = $this->get(route('paquetes.detalle', $india));
        $responseIndia->assertStatus(200);
        $responseIndia->assertSee('Taj Mahal');
    }

    public function test_detalles_de_alojamientos_de_la_home_cargan_con_acerca_de_este_alojamiento(): void
    {
        $hoteles = [
            'tokio-ritz' => 'The Ritz-Carlton, Tokyo',
            'cartagena-oz' => 'Oz Hotel Luxury',
            'madrid-riu' => 'Hotel Riu Plaza España',
            'cusco-monasterio' => 'Belmond Monasterio, Cusco',
            'barcelona-arts' => 'Hotel Arts Barcelona',
            'lima-miraflores' => 'Miraflores Park, Lima',
        ];

        foreach ($hoteles as $codigo => $nombre) {
            $response = $this->get(route('alojamientos.detalle', ['codigo' => $codigo]));
            $response->assertStatus(200);
            $response->assertSee($nombre);
            $response->assertSee('Acerca de este alojamiento');
            $response->assertSee('Reserva tu alojamiento');
            $response->assertSee('Continuar con la reserva');
        }
    }

    public function test_catalogo_de_alojamientos_muestra_los_hoteles_destacados(): void
    {
        $response = $this->get(route('alojamientos'));
        $response->assertStatus(200);
        $response->assertSee('The Ritz-Carlton, Tokyo');
        $response->assertSee('Oz Hotel Luxury');
        $response->assertSee('Hotel Riu Plaza España');
        $response->assertSee('Belmond Monasterio, Cusco');
        $response->assertSee('Hotel Arts Barcelona');
    }
}

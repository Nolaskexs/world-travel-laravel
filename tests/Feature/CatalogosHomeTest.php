<?php

namespace Tests\Feature;

use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\User;
use Database\Seeders\AlojamientoSeeder;
use Database\Seeders\PaquetesOfertasSeeder;
use Database\Seeders\ReservaSeeder;
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

    public function test_catalogo_de_paquetes_permite_busqueda_por_destino_y_presupuesto(): void
    {
        $response = $this->get(route('paquetes'));
        $response->assertStatus(200);
        $response->assertSee('Encuentra el viaje perfecto');
        $response->assertSee('¿A dónde quieres viajar?');
        $response->assertSee('Cusco');

        // Filtrar por destino Cusco y presupuesto máximo 600
        $filtroResponse = $this->get(route('paquetes', ['q' => 'Cusco', 'maximo' => 600]));
        $filtroResponse->assertStatus(200);
        $filtroResponse->assertSee('Cusco');
        $filtroResponse->assertDontSee('Vuelo a Tokio');
    }

    public function test_catalogo_de_paquetes_tiene_opciones_para_todos_los_rangos_de_bali(): void
    {
        // Con presupuesto de 500 para Bali
        $respBali500 = $this->get(route('paquetes', ['q' => 'Bali', 'maximo' => 500]));
        $respBali500->assertStatus(200);
        $respBali500->assertSee('Escapada express a Bali y Ubud');

        // Con presupuesto de 1000 para Bali
        $respBali1000 = $this->get(route('paquetes', ['q' => 'Bali', 'maximo' => 1000]));
        $respBali1000->assertStatus(200);
        $respBali1000->assertSee('Playas de Bali y Seminyak');

        // Con duración de hasta 5 días para Bali
        $respBaliDuracion = $this->get(route('paquetes', ['q' => 'Bali', 'duracion' => 5]));
        $respBaliDuracion->assertStatus(200);
        $respBaliDuracion->assertSee('Playas de Bali y Seminyak');
    }

    public function test_confirmar_reserva_muestra_imagen_correcta_para_india(): void
    {
        $indiaPaquete = Paquete::where('destino', 'India')->first();
        $this->assertNotNull($indiaPaquete);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'testuser@worldtravel.com',
            'password' => bcrypt('password123'),
            'rol' => 'cliente',
        ]);

        $response = $this->actingAs($user)->get(route('reservas.confirmar', ['paquete_id' => $indiaPaquete->id]));
        $response->assertStatus(200);
        $response->assertSee('India milenaria y el Taj Mahal');
        $response->assertSee('icons/india.jpg');
    }

    public function test_itinerarios_muestra_autocompletado_y_alerta_de_presupuesto(): void
    {
        $user = User::create([
            'name' => 'Itin User',
            'email' => 'itinuser@worldtravel.com',
            'password' => bcrypt('password123'),
            'rol' => 'cliente',
        ]);

        $response = $this->actingAs($user)->get(route('itinerarios.index'));
        $response->assertStatus(200);
        $response->assertSee('Crear itinerario');
        $response->assertSee('it-sidebar-budget-status');
        $response->assertSee('wt-dest-suggest');
        $response->assertSee('CATALOGO =', false);
    }

    public function test_dashboards_tienen_reservas_por_categoria(): void
    {
        $this->seed(ReservaSeeder::class);
        $this->assertGreaterThanOrEqual(2, Reserva::count());
    }
}

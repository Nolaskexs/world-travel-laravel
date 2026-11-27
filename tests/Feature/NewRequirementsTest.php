<?php

namespace Tests\Feature;

use App\Models\Oferta;
use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\User;
use Database\Seeders\AlojamientoSeeder;
use Database\Seeders\DestinosSeeder;
use Database\Seeders\PaquetesOfertasSeeder;
use Database\Seeders\ReservaSeeder;
use Database\Seeders\VueloSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewRequirementsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $cliente;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DestinosSeeder::class);
        $this->seed(VueloSeeder::class);
        $this->seed(PaquetesOfertasSeeder::class);
        $this->seed(AlojamientoSeeder::class);
        $this->seed(ReservaSeeder::class);

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_test@worldtravel.com'],
            ['name' => 'Admin Test', 'password' => bcrypt('password123'), 'rol' => 'admin']
        );

        $this->cliente = User::firstOrCreate(
            ['email' => 'cliente_test@worldtravel.com'],
            ['name' => 'Cliente Test', 'password' => bcrypt('password123'), 'rol' => 'cliente']
        );
    }

    public function test_busqueda_con_origen_y_destino_iguales_alerta_y_no_muestra_vuelos_ficticios(): void
    {
        $response = $this->get(route('search', [
            'origin' => 'Cancún',
            'destination' => 'Cancún',
            'departure' => '2026-12-10',
        ]));

        $response->assertOk();
        $response->assertSee('El origen y el destino no pueden ser iguales');
        $response->assertSee('Ruta de vuelo no válida');
    }

    public function test_ayuda_se_oculta_en_header_cuando_usuario_es_administrador(): void
    {
        $responseCliente = $this->actingAs($this->cliente)->get(route('inicio'));
        $responseCliente->assertOk();
        $responseCliente->assertSee('Ayuda');

        $responseAdmin = $this->actingAs($this->admin)->get(route('inicio'));
        $responseAdmin->assertOk();
        $responseAdmin->assertDontSee('>Ayuda<', false);
    }

    public function test_la_gran_mayoria_de_paquetes_tienen_vuelo_incluido(): void
    {
        $totalPaquetes = Paquete::count();
        $conVuelo = Paquete::where('incluye_vuelo', true)->count();

        $this->assertGreaterThan(20, $totalPaquetes);
        $this->assertGreaterThan($totalPaquetes * 0.8, $conVuelo);
    }

    public function test_dashboard_del_cliente_carga_categorias_y_asientos(): void
    {
        $clienteDemo = User::where('email', 'cliente@worldtravel.com')->firstOrFail();
        $response = $this->actingAs($clienteDemo)->get(route('dashboard'));

        $response->assertOk();
        $response->assertDontSee('Tramos de Vuelo y Asientos');
        $response->assertSee('Tus Reservas por Categoría');
        $response->assertSee('Vuelos');
        $response->assertSee('Alojamientos');
        $response->assertSee('Paquetes');
        $response->assertSee('Ofertas');
        $response->assertSee('Asiento');
    }

    public function test_reserva_de_ofertas_guarda_pasajeros_y_asientos(): void
    {
        $oferta = Oferta::with('paquete')->firstOrFail();

        $response = $this->actingAs($this->cliente)->post(route('reservas.ofertas.store'), [
            'nombre' => 'Pasajero Titular',
            'email' => 'titular@test.com',
            'telefono' => '999888777',
            'documento' => '70809010',
            'oferta_id' => $oferta->id,
            'fecha_viaje' => now()->addDays(20)->toDateString(),
            'num_pasajeros' => 3,
            'asientos' => '12A, 12B, 12C',
        ]);

        $reserva = Reserva::where('oferta_id', $oferta->id)
            ->where('user_id', $this->cliente->id)
            ->first();

        $this->assertNotNull($reserva);
        $this->assertEquals(3, $reserva->num_pasajeros);
        $this->assertCount(3, $reserva->pasajeros);
        $this->assertDatabaseHas('reserva_pasajeros', [
            'reserva_id' => $reserva->id,
            'asiento' => '12A',
        ]);
        $this->assertDatabaseHas('reserva_pasajeros', [
            'reserva_id' => $reserva->id,
            'asiento' => '12B',
        ]);
        $this->assertDatabaseHas('reserva_pasajeros', [
            'reserva_id' => $reserva->id,
            'asiento' => '12C',
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Alojamiento;
use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\User;
use Database\Seeders\AlojamientoSeeder;
use Database\Seeders\PaquetesOfertasSeeder;
use Database\Seeders\ReservaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminBackendTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $cliente;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_admin_puede_ver_dashboard_con_metricas(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Ingresos Totales');
        $response->assertSee('Reservas Registradas');
        $response->assertSee('Paquetes Disponibles');
    }

    public function test_cliente_no_puede_acceder_al_panel_admin(): void
    {
        $response = $this->actingAs($this->cliente)->get(route('admin.dashboard'));

        // El middleware 'can:administrar' bloquea clientes normales
        $this->assertTrue(in_array($response->status(), [403, 302], true));
    }

    public function test_admin_puede_listar_reservas_y_ver_detalles(): void
    {
        $reserva = Reserva::first();
        $this->assertNotNull($reserva);

        $response = $this->actingAs($this->admin)->get(route('admin.reservas.index'));
        $response->assertOk();
        $response->assertSee('Gestión de reservas');

        $detalleResponse = $this->actingAs($this->admin)->get(route('admin.reservas.show', $reserva->id));
        $detalleResponse->assertOk();
        $detalleResponse->assertSee("Reserva {$reserva->id}");
    }

    public function test_admin_puede_actualizar_estado_de_reserva(): void
    {
        $reserva = Reserva::where('estado', 'pendiente')->first() ?? Reserva::first();
        $this->assertNotNull($reserva);

        $response = $this->actingAs($this->admin)->patch(route('admin.reservas.update', $reserva->id), [
            'estado' => 'confirmada',
        ]);

        $response->assertRedirect(route('admin.reservas.show', $reserva->id));
        $this->assertDatabaseHas('reservas', [
            'id' => $reserva->id,
            'estado' => 'confirmada',
        ]);
    }

    public function test_admin_puede_gestionar_crud_de_alojamientos(): void
    {
        // 1. Listar alojamientos
        $indexResp = $this->actingAs($this->admin)->get(route('admin.alojamientos.index'));
        $indexResp->assertOk();
        $indexResp->assertSee('Gestión de Alojamientos');

        // 2. Formulario de creación
        $createResp = $this->actingAs($this->admin)->get(route('admin.alojamientos.create'));
        $createResp->assertOk();

        // 3. Crear nuevo alojamiento
        $storeResp = $this->actingAs($this->admin)->post(route('admin.alojamientos.store'), [
            'nombre' => 'Gran Hotel Test',
            'codigo' => 'gran-hotel-test',
            'tipo' => 'hotel',
            'direccion' => 'Avenida Principal 123, Cusco, Perú',
            'precio_noche' => 150.00,
            'moneda' => 'USD',
            'estrellas' => 4,
            'puntuacion' => 4.6,
            'descripcion' => 'Hotel de prueba con excelente servicio.',
            'servicios_texto' => "Wi-Fi\nPiscina\nDesayuno",
            'activo' => 1,
        ]);

        $storeResp->assertRedirect(route('admin.alojamientos.index'));
        $this->assertDatabaseHas('alojamientos', [
            'codigo' => 'gran-hotel-test',
            'nombre' => 'Gran Hotel Test',
        ]);

        $hotelCreado = Alojamiento::where('codigo', 'gran-hotel-test')->firstOrFail();

        // 4. Editar alojamiento
        $editResp = $this->actingAs($this->admin)->get(route('admin.alojamientos.edit', $hotelCreado->id));
        $editResp->assertOk();

        // 5. Actualizar alojamiento
        $updateResp = $this->actingAs($this->admin)->put(route('admin.alojamientos.update', $hotelCreado->id), [
            'nombre' => 'Gran Hotel Test Actualizado',
            'codigo' => 'gran-hotel-test',
            'tipo' => 'hotel',
            'direccion' => 'Avenida Principal 456, Cusco, Perú',
            'precio_noche' => 180.00,
            'moneda' => 'USD',
            'estrellas' => 5,
            'descripcion' => 'Descripción actualizada.',
            'activo' => 1,
        ]);

        $updateResp->assertRedirect(route('admin.alojamientos.index'));
        $this->assertDatabaseHas('alojamientos', [
            'id' => $hotelCreado->id,
            'nombre' => 'Gran Hotel Test Actualizado',
        ]);

        // 6. Eliminar alojamiento
        $deleteResp = $this->actingAs($this->admin)->delete(route('admin.alojamientos.destroy', $hotelCreado->id));
        $deleteResp->assertRedirect(route('admin.alojamientos.index'));
        $this->assertDatabaseMissing('alojamientos', [
            'id' => $hotelCreado->id,
        ]);
    }

    public function test_cliente_puede_ver_dashboard_con_sus_reservas(): void
    {
        // Asociar una reserva al cliente de prueba
        $paquete = Paquete::first();
        $reserva = Reserva::create([
            'user_id' => $this->cliente->id,
            'paquete_id' => $paquete->id,
            'tipo_servicio' => 'paquete',
            'fecha_viaje' => now()->addDays(20)->toDateString(),
            'num_pasajeros' => 2,
            'total' => 1500.00,
            'estado' => 'confirmada',
        ]);

        $response = $this->actingAs($this->cliente)->get(route('dashboard'));
        $response->assertOk();
        $response->assertSee('US$ 1,500.00');
        $response->assertSee('2 pasajeros');
    }
}

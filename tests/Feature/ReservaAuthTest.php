<?php

namespace Tests\Feature;

use App\Models\Destino;
use App\Models\Paquete;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaAuthTest extends TestCase
{
    use RefreshDatabase;

    protected Paquete $paquete;

    protected function setUp(): void
    {
        parent::setUp();

        $destino = Destino::create([
            'nombre' => 'Roma',
            'pais' => 'Italia',
            'descripcion' => 'Ciudad Eterna',
        ]);

        $this->paquete = Paquete::create([
            'destino_id' => $destino->id,
            'titulo' => 'Vuelo a Roma',
            'destino' => 'Roma',
            'pais' => 'Italia',
            'continente' => 'Europa',
            'descripcion' => 'Descubre la magia de Roma.',
            'duracion_dias' => 7,
            'precio' => 1230.00,
            'moneda' => 'USD',
            'activo' => true,
        ]);
    }

    public function test_usuario_no_autenticado_es_redirigido_al_login_al_intentar_confirmar_reserva(): void
    {
        $response = $this->get(route('reservas.confirmar', ['paquete_id' => $this->paquete->id]));
        $response->assertRedirect(route('login'));
    }

    public function test_usuario_no_autenticado_es_redirigido_al_login_al_intentar_guardar_reserva(): void
    {
        $response = $this->post(route('reservas.store'), [
            'nombre' => 'Viajero Anónimo',
            'email' => 'anonimo@example.com',
            'documento' => '12345678',
            'paquete_id' => $this->paquete->id,
            'fecha_viaje' => now()->addDays(10)->format('Y-m-d'),
            'num_pasajeros' => 1,
            'total' => 1230.00,
        ]);
        $response->assertRedirect(route('login'));
    }

    public function test_cliente_autenticado_puede_acceder_a_confirmar_reserva(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($cliente)->get(route('reservas.confirmar', ['paquete_id' => $this->paquete->id]));
        $response->assertStatus(200);
        $response->assertSee('Vuelo a Roma');
        $response->assertSee($cliente->name);
    }

    public function test_administrador_autenticado_puede_acceder_a_confirmar_reserva(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $response = $this->actingAs($admin)->get(route('reservas.confirmar', ['paquete_id' => $this->paquete->id]));
        $response->assertStatus(200);
        $response->assertSee('Vuelo a Roma');
        $response->assertSee($admin->name);
    }

    public function test_login_muestra_aviso_cuando_proviene_de_intento_de_reserva(): void
    {
        // Simular intento de acceso guardado en sesión
        $response = $this->withSession(['url.intended' => route('reservas.confirmar', ['paquete_id' => $this->paquete->id])])
            ->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Inicia sesión como');
        $response->assertSee('para continuar con tu reserva');
    }
}

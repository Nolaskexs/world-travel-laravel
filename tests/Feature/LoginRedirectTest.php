<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_es_redirigido_a_admin_al_iniciar_sesion(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin_test@worldtravel.com',
            'password' => bcrypt('Admin12345'),
            'rol' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin_test@worldtravel.com',
            'password' => 'Admin12345',
        ]);

        $response->assertRedirect('/admin');
    }

    public function test_cliente_normal_es_redirigido_a_dashboard_al_iniciar_sesion(): void
    {
        $cliente = User::factory()->create([
            'email' => 'cliente_test@worldtravel.com',
            'password' => bcrypt('Cliente12345'),
            'rol' => 'cliente',
        ]);

        $response = $this->post('/login', [
            'email' => 'cliente_test@worldtravel.com',
            'password' => 'Cliente12345',
        ]);

        $response->assertRedirect('/dashboard');
    }

    public function test_administrador_accediendo_a_dashboard_es_redirigido_a_admin(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $response = $this->actingAs($admin)->get('/dashboard');

        $response->assertRedirect('/admin');
    }

    public function test_cliente_accediendo_a_dashboard_entra_con_exito(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($cliente)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_cliente_con_reservas_accediendo_a_dashboard_entra_con_exito(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);
        $paquete = \App\Models\Paquete::create([
            'titulo' => 'Vuelo a Roma',
            'descripcion' => 'Paquete turístico de prueba en Roma con vuelos y hotel.',
            'destino' => 'Roma',
            'pais' => 'Italia',
            'continente' => 'Europa',
            'precio' => 1200.00,
            'duracion_dias' => 7,
            'destacado' => true,
            'activo' => true,
        ]);
        \App\Models\Reserva::create([
            'user_id' => $cliente->id,
            'paquete_id' => $paquete->id,
            'fecha_viaje' => now()->addDays(5),
            'num_pasajeros' => 1,
            'total' => 1200.00,
            'estado' => 'pendiente',
        ]);

        $response = $this->actingAs($cliente)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Pagar Reserva');
    }

    public function test_administrador_no_ve_enlace_mis_viajes_en_el_header(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $response = $this->actingAs($admin)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Mis viajes');
        $response->assertSee('Panel Admin');
    }

    public function test_cliente_ve_enlace_mis_viajes_en_el_header(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($cliente)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Mis viajes');
    }

    public function test_invitado_ve_enlace_mis_viajes_en_el_header(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Mis viajes');
    }

    public function test_botones_sociales_en_login_tienen_los_enlaces_correctos(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('https://accounts.google.com/v3/signin/identifier');
        $response->assertSee('https://www.facebook.com/login/');
    }

    public function test_admin_accediendo_a_admin_dashboard_entra_con_exito(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);

        // Verificar que las secciones del admin responden con 200
        $this->actingAs($admin)->get('/admin/reservas')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/paquetes')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/destinos')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/clientes')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/facturacion')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/reclamaciones')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/ajustes')->assertStatus(200);
    }

    public function test_botones_sociales_en_register_tienen_los_enlaces_correctos(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('https://accounts.google.com/v3/signin/identifier');
        $response->assertSee('https://www.facebook.com/login/');
    }
}

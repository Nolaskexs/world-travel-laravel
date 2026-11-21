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

    public function test_botones_sociales_en_register_tienen_los_enlaces_correctos(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('https://accounts.google.com/v3/signin/identifier');
        $response->assertSee('https://www.facebook.com/login/');
    }
}

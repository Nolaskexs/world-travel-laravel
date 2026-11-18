<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroTest extends TestCase
{
    use RefreshDatabase;

    public function test_nuevo_usuario_se_registra_con_dni_y_rol_cliente(): void
    {
        $response = $this->post('/register', [
            'firstName' => 'Carlos',
            'lastName' => 'Sanchez',
            'email' => 'carlos.sanchez@testviajes.com',
            'docId' => '72819203',
            'dial' => '+51',
            'phone' => '912345678',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();

        $user = User::where('email', 'carlos.sanchez@testviajes.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('cliente', $user->rol);
        $this->assertEquals('72819203', $user->dni);
        $this->assertNotEquals('admin', $user->rol);
        $this->assertNotNull($user);
        $this->assertEquals('cliente', $user->rol);
        $this->assertEquals('72819203', $user->dni);
        $this->assertNotEquals('admin', $user->rol);

        // Limpiar usuario de prueba
        $user->delete();
    }
}

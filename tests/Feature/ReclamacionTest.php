<?php

namespace Tests\Feature;

use App\Models\Reclamacion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReclamacionTest extends TestCase
{
    use RefreshDatabase;

    public function test_formulario_publico_reclamaciones_carga_correctamente(): void
    {
        $response = $this->get('/libro-de-reclamaciones');
        $response->assertStatus(200);
        $response->assertSee('Hoja de Reclamación');
        $response->assertSee('World Travel Perú S.A.C.');
    }

    public function test_usuario_puede_enviar_hoja_de_reclamacion_y_se_guarda_en_bd(): void
    {
        $payload = [
            'tipo_persona' => 'natural',
            'nombre' => 'Ana Morales Ríos',
            'tipo_documento' => 'DNI',
            'documento' => '72910482',
            'email' => 'ana.morales@example.com',
            'telefono' => '987123456',
            'pais' => 'Perú',
            'producto' => 'Vuelo Lima - Cancún',
            'reserva' => '501',
            'motivo' => 'reclamo_vuelo',
            'detalle' => 'Hubo una sobreventa de asientos y no pude abordar en el horario programado.',
            'pedido' => 'Reembolso total de la penalidad y compensación en millas.',
            'moneda' => 'USD',
            'monto' => 250.00,
            'formato_respuesta' => 'digital',
        ];

        $response = $this->post('/libro-de-reclamaciones', $payload);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reclamaciones', [
            'nombre' => 'Ana Morales Ríos',
            'documento' => '72910482',
            'email' => 'ana.morales@example.com',
            'tipo' => 'reclamo',
            'estado' => 'pendiente',
        ]);
    }

    public function test_administrador_puede_ver_listado_y_detalle_de_reclamaciones(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $rec = Reclamacion::create([
            'codigo' => 'REC-2026-9999',
            'tipo_persona' => 'natural',
            'nombre' => 'Pedro Castillo',
            'tipo_documento' => 'DNI',
            'documento' => '10293847',
            'email' => 'pedro@example.com',
            'pais' => 'Perú',
            'producto' => 'Tour Montaña 7 Colores',
            'tipo' => 'reclamo',
            'motivo' => 'reclamo_vuelo',
            'detalle' => 'Detalle de prueba para reclamo.',
            'pedido' => 'Pedido de prueba.',
            'moneda' => 'PEN',
            'formato_respuesta' => 'digital',
            'estado' => 'pendiente',
        ]);

        $responseIndex = $this->actingAs($admin)->get('/admin/reclamaciones');
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('REC-2026-9999');
        $responseIndex->assertSee('Pedro Castillo');

        $responseShow = $this->actingAs($admin)->get('/admin/reclamaciones/' . $rec->id);
        $responseShow->assertStatus(200);
        $responseShow->assertSee('REC-2026-9999');
        $responseShow->assertSee('Detalle de prueba para reclamo.');
    }

    public function test_administrador_puede_responder_y_actualizar_estado_de_reclamacion(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $rec = Reclamacion::create([
            'codigo' => 'REC-2026-8888',
            'tipo_persona' => 'natural',
            'nombre' => 'Lucía Ramos',
            'tipo_documento' => 'DNI',
            'documento' => '84920183',
            'email' => 'lucia@example.com',
            'pais' => 'Perú',
            'producto' => 'Vuelo Cusco - Lima',
            'tipo' => 'queja',
            'motivo' => 'queja_atencion',
            'detalle' => 'Queja de atención de prueba.',
            'pedido' => 'Disculpas del personal.',
            'moneda' => 'PEN',
            'formato_respuesta' => 'digital',
            'estado' => 'pendiente',
        ]);

        $response = $this->actingAs($admin)->put('/admin/reclamaciones/' . $rec->id, [
            'estado' => 'atendido',
            'respuesta' => 'Estimada Lucía, hemos tomado las medidas correctivas con el personal correspondiente.',
        ]);

        $response->assertRedirect('/admin/reclamaciones/' . $rec->id);

        $this->assertDatabaseHas('reclamaciones', [
            'id' => $rec->id,
            'estado' => 'atendido',
            'respondido_por' => $admin->id,
        ]);
    }

    public function test_usuario_normal_no_puede_acceder_al_panel_de_reclamaciones(): void
    {
        $cliente = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($cliente)->get('/admin/reclamaciones');
        $response->assertStatus(403);
    }
}

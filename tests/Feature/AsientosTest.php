<?php

namespace Tests\Feature;

use App\Models\Destino;
use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\ReservaPasajero;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsientosTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_no_autenticado_es_redirigido_al_login_al_intentar_elegir_asientos(): void
    {
        $response = $this->get('/asientos?adultos=2&menores=1&origin=Lima&destination=Tokio&origin_iata=LIM&destination_iata=HND&codigo_vuelo=WT-8183');
        $response->assertRedirect(route('login'));
    }

    public function test_pantalla_asientos_carga_correctamente_para_usuario_autenticado(): void
    {
        $user = User::factory()->create(['rol' => 'cliente']);

        $response = $this->actingAs($user)->get('/asientos?adultos=2&menores=1&origin=Lima&destination=Tokio&origin_iata=LIM&destination_iata=HND&codigo_vuelo=WT-8183');
        $response->assertStatus(200);
        $response->assertSee('World Travel');
        $response->assertSee('Lima (LIM)');
        $response->assertSee('Tokio (HND)');
        $response->assertSee('WT-8183');
        $response->assertSee('Adulto 1');
        $response->assertSee('Adulto 2');
        $response->assertSee('Menor 1');
    }

    public function test_pantalla_asientos_carga_correctamente_para_administrador(): void
    {
        $admin = User::factory()->create(['rol' => 'admin']);

        $response = $this->actingAs($admin)->get('/asientos?adultos=1&origin=Lima&destination=Tokio');
        $response->assertStatus(200);
        $response->assertSee('World Travel');
    }

    public function test_guardar_asientos_en_checkin_actualiza_pasajeros(): void
    {
        $user = User::factory()->create(['rol' => 'cliente']);
        $destino = Destino::create([
            'nombre' => 'Cusco',
            'pais' => 'Perú',
            'descripcion' => 'Ciudad Imperial',
        ]);
        $paquete = Paquete::create([
            'destino_id' => $destino->id,
            'titulo' => 'Cusco Mágico',
            'destino' => 'Cusco',
            'pais' => 'Perú',
            'continente' => 'America',
            'descripcion' => 'Tour a Cusco',
            'duracion_dias' => 4,
            'precio' => 400.00,
            'moneda' => 'USD',
            'activo' => true,
        ]);

        $reserva = Reserva::create([
            'user_id' => $user->id,
            'paquete_id' => $paquete->id,
            'fecha_viaje' => now()->addDays(2),
            'num_pasajeros' => 2,
            'total' => 800.00,
            'estado' => 'confirmada',
        ]);

        $p1 = ReservaPasajero::create([
            'reserva_id' => $reserva->id,
            'nombre' => 'Juan Pérez',
            'documento' => '71829304',
        ]);
        $p2 = ReservaPasajero::create([
            'reserva_id' => $reserva->id,
            'nombre' => 'María Gómez',
            'documento' => '72918273',
        ]);

        $response = $this->actingAs($user)->post('/asientos', [
            'reserva_id' => $reserva->id,
            'asientos' => ['12A', '12C'],
        ]);

        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('success');

        $this->assertEquals('12A', $p1->fresh()->asiento);
        $this->assertEquals('12C', $p2->fresh()->asiento);
    }
}

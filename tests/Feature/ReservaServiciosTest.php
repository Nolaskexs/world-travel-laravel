<?php

namespace Tests\Feature;

use App\Models\Alojamiento;
use App\Models\Oferta;
use App\Models\Reserva;
use App\Models\User;
use Database\Seeders\AlojamientoSeeder;
use Database\Seeders\PaquetesOfertasSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservaServiciosTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PaquetesOfertasSeeder::class);
        $this->seed(AlojamientoSeeder::class);
    }

    private function cliente(): User
    {
        return User::factory()->create([
            'rol' => 'cliente',
            'name' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'dni' => '12345678',
            'telefono' => '987654321',
        ]);
    }

    public function test_invitado_es_redirigido_al_login_en_los_formularios_de_los_servicios(): void
    {
        $alojamiento = Alojamiento::where('codigo', 'cartagena-oz')->firstOrFail();
        $oferta = Oferta::where('descuento_porcentaje', 22)->firstOrFail();

        $this->get(route('reservas.confirmar.alojamientos', ['alojamiento' => $alojamiento->codigo]))
            ->assertRedirect(route('login'));

        $this->get(route('reservas.confirmar.vuelos'))
            ->assertRedirect(route('login'));

        $this->get(route('reservas.confirmar.ofertas', ['oferta_id' => $oferta->id]))
            ->assertRedirect(route('login'));
    }

    public function test_invitado_no_puede_registrar_reservas_de_los_servicios(): void
    {
        $alojamiento = Alojamiento::where('codigo', 'cartagena-oz')->firstOrFail();
        $oferta = Oferta::where('descuento_porcentaje', 22)->firstOrFail();

        $this->post(route('reservas.alojamientos.store'), [
            'alojamiento_id' => $alojamiento->id,
        ])->assertRedirect(route('login'));

        $this->post(route('reservas.vuelos.store'), [
            'origen' => 'Lima',
        ])->assertRedirect(route('login'));

        $this->post(route('reservas.ofertas.store'), [
            'oferta_id' => $oferta->id,
        ])->assertRedirect(route('login'));

        $this->assertDatabaseCount('reservas', 0);
    }

    public function test_formulario_de_alojamiento_muestra_el_servicio_y_los_datos_del_titular(): void
    {
        $cliente = $this->cliente();

        $response = $this->actingAs($cliente)->get(route('reservas.confirmar.alojamientos', [
            'alojamiento' => 'tokio-ritz',
            'entrada' => '2026-12-10',
            'salida' => '2026-12-13',
            'huespedes' => 2,
        ]));

        $response->assertOk()
            ->assertSee('Confirma tu reserva de alojamiento')
            ->assertSee('The Ritz-Carlton, Tokyo')
            ->assertSee('Confirmar Reserva')
            ->assertSee('Ana Pérez')
            ->assertSee('12345678');
    }

    public function test_formulario_de_alojamiento_muestra_otro_alojamiento_solicitado(): void
    {
        $cliente = $this->cliente();

        $response = $this->actingAs($cliente)->get(route('reservas.confirmar.alojamientos', [
            'alojamiento' => 'cartagena-oz',
        ]));

        $response->assertOk()
            ->assertSee('Oz Hotel Luxury')
            ->assertSee('Cartagena de Indias, Colombia');
    }

    public function test_formulario_de_vuelos_muestra_la_ruta_y_la_cotizacion(): void
    {
        $cliente = $this->cliente();

        $response = $this->actingAs($cliente)->get(route('reservas.confirmar.vuelos', [
            'origin' => 'Lima',
            'destination' => 'Tokio',
            'origin_iata' => 'LIM',
            'destination_iata' => 'HND',
            'codigo_vuelo' => 'WT-8183',
            'adultos' => 2,
            'menores' => 1,
        ]));

        $response->assertOk()
            ->assertSee('Confirma tu reserva de vuelo')
            ->assertSee('Lima (LIM)')
            ->assertSee('Tokio (HND)')
            ->assertSee('WT-8183')
            ->assertSee('Confirmar Reserva');
    }

    public function test_formulario_de_ofertas_muestra_la_promocion_seleccionada(): void
    {
        $cliente = $this->cliente();
        $oferta = Oferta::where('descuento_porcentaje', 22)->firstOrFail();

        $response = $this->actingAs($cliente)->get(route('reservas.confirmar.ofertas', [
            'oferta_id' => $oferta->id,
            'num_pasajeros' => 2,
        ]));

        $response->assertOk()
            ->assertSee('Confirma tu reserva de oferta')
            ->assertSee($oferta->titulo)
            ->assertSee('US$ 3,244.80')
            ->assertSee('Confirmar Reserva');
    }

    public function test_registro_de_alojamiento_guarda_la_reserva_y_muestra_confirmacion(): void
    {
        $cliente = $this->cliente();
        $alojamiento = Alojamiento::where('codigo', 'cartagena-oz')->firstOrFail();

        $response = $this->actingAs($cliente)->post(route('reservas.alojamientos.store'), [
            'nombre' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'telefono' => '987654321',
            'documento' => '12345678',
            'alojamiento_id' => $alojamiento->id,
            'fecha_entrada' => '2026-12-10',
            'fecha_salida' => '2026-12-13',
            'num_pasajeros' => 2,
        ]);

        $reserva = Reserva::query()->latest('id')->firstOrFail();

        $response->assertRedirect(route('reservas.exito', $reserva));

        $this->assertSame('alojamiento', $reserva->tipo_servicio);
        $this->assertSame($alojamiento->id, $reserva->alojamiento_id);
        $this->assertNull($reserva->paquete_id);
        $this->assertSame(3, $reserva->noches);
        $this->assertSame('222.00', $reserva->total);
        $this->assertDatabaseHas('reserva_pasajeros', [
            'reserva_id' => $reserva->id,
            'nombre' => 'Ana Pérez',
            'documento' => '12345678',
        ]);

        $this->get(route('reservas.exito', $reserva))
            ->assertOk()
            ->assertSee('¡Reserva realizada correctamente!')
            ->assertSee('¡Reserva de alojamiento realizada correctamente!');
    }

    public function test_registro_de_vuelo_guarda_la_reserva_y_muestra_confirmacion(): void
    {
        $cliente = $this->cliente();

        $response = $this->actingAs($cliente)->post(route('reservas.vuelos.store'), [
            'nombre' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'documento' => '12345678',
            'tipo_documento' => 'Pasaporte',
            'origen' => 'Lima',
            'destino' => 'Tokio',
            'origin_iata' => 'LIM',
            'destination_iata' => 'HND',
            'codigo_vuelo' => 'WT-8183',
            'precio_adulto' => 400,
            'impuestos' => 50,
            'fecha_viaje' => '2026-12-10',
            'adultos' => 2,
            'menores' => 1,
            'infantes' => 0,
            'asientos' => '12A, 12B, 12C',
        ]);

        $reserva = Reserva::query()->latest('id')->firstOrFail();

        $response->assertRedirect(route('reservas.exito', $reserva));

        $this->assertSame('vuelo', $reserva->tipo_servicio);
        $this->assertNull($reserva->paquete_id);
        $this->assertSame('WT-8183 · Lima → Tokio', $reserva->detalle_servicio);
        $this->assertSame('12A, 12B, 12C', $reserva->asientos);
        $this->assertSame('1350.00', $reserva->total);
        $this->assertDatabaseHas('reserva_pasajeros', [
            'reserva_id' => $reserva->id,
            'nombre' => 'Ana Pérez',
            'asiento' => '12A',
        ]);

        $this->get(route('reservas.exito', $reserva))
            ->assertOk()
            ->assertSee('¡Reserva realizada correctamente!')
            ->assertSee('¡Reserva de vuelo realizada correctamente!');
    }

    public function test_registro_de_oferta_guarda_la_reserva_y_muestra_confirmacion(): void
    {
        $cliente = $this->cliente();
        $oferta = Oferta::where('descuento_porcentaje', 22)->firstOrFail();

        $response = $this->actingAs($cliente)->post(route('reservas.ofertas.store'), [
            'nombre' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'documento' => '12345678',
            'oferta_id' => $oferta->id,
            'fecha_viaje' => '2026-12-10',
            'num_pasajeros' => 2,
        ]);

        $reserva = Reserva::query()->latest('id')->firstOrFail();

        $response->assertRedirect(route('reservas.exito', $reserva));

        $this->assertSame('oferta', $reserva->tipo_servicio);
        $this->assertSame($oferta->id, $reserva->oferta_id);
        $this->assertNull($reserva->paquete_id);
        $this->assertSame('3244.80', $reserva->total);
        $this->assertDatabaseHas('reserva_pasajeros', [
            'reserva_id' => $reserva->id,
            'nombre' => 'Ana Pérez',
            'documento' => '12345678',
        ]);

        $this->get(route('reservas.exito', $reserva))
            ->assertOk()
            ->assertSee('¡Reserva realizada correctamente!')
            ->assertSee('¡Reserva de oferta realizada correctamente!');
    }

    public function test_registro_de_alojamiento_rechaza_datos_obligatorios(): void
    {
        $alojamiento = Alojamiento::where('codigo', 'cartagena-oz')->firstOrFail();

        $response = $this->actingAs($this->cliente())->post(route('reservas.alojamientos.store'), [
            'alojamiento_id' => $alojamiento->id,
            'fecha_entrada' => '2026-12-10',
            'fecha_salida' => '2026-12-13',
        ]);

        $response->assertSessionHasErrors(['nombre', 'email', 'documento', 'num_pasajeros']);
        $this->assertDatabaseCount('reservas', 0);
    }

    public function test_registro_de_alojamiento_rechaza_salida_anterior_a_la_entrada(): void
    {
        $alojamiento = Alojamiento::where('codigo', 'cartagena-oz')->firstOrFail();

        $response = $this->actingAs($this->cliente())->post(route('reservas.alojamientos.store'), [
            'nombre' => 'Ana Pérez',
            'email' => 'ana@example.com',
            'documento' => '12345678',
            'alojamiento_id' => $alojamiento->id,
            'fecha_entrada' => '2026-12-10',
            'fecha_salida' => '2026-12-09',
            'num_pasajeros' => 2,
        ]);

        $response->assertSessionHasErrors(['fecha_salida']);
        $this->assertDatabaseCount('reservas', 0);
    }
}

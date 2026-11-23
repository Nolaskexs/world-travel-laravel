<?php

namespace Tests\Feature;

use App\Models\Destino;
use App\Models\Paquete;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EndToEndReservaFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Paquete $paquete;
    protected User $cliente;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $destino = Destino::create([
            'nombre' => 'Tokio',
            'pais' => 'Japón',
            'descripcion' => 'Capital de Japón',
        ]);

        $this->paquete = Paquete::create([
            'destino_id' => $destino->id,
            'titulo' => 'Descubre Tokio y Kioto',
            'destino' => 'Tokio',
            'pais' => 'Japón',
            'continente' => 'Asia',
            'descripcion' => 'Una experiencia inolvidable por los templos y rascacielos de Japón.',
            'duracion_dias' => 10,
            'precio' => 1800.00,
            'moneda' => 'USD',
            'destacado' => true,
            'activo' => true,
        ]);

        $this->cliente = User::factory()->create([
            'name' => 'Carlos Viajero',
            'email' => 'carlos@example.com',
            'password' => bcrypt('Password123'),
            'rol' => 'cliente',
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Administrador WT',
            'email' => 'admin@worldtravel.com',
            'password' => bcrypt('AdminPass123'),
            'rol' => 'admin',
        ]);
    }

    public function test_1_invitado_busca_y_al_intentar_reservar_es_redirigido_a_login(): void
    {
        // 1. Invitado busca vuelos/paquetes
        $responseBusqueda = $this->get(route('search', ['origin' => 'Lima', 'destination' => 'Tokio']));
        $responseBusqueda->assertStatus(200);

        // 2. Invitado intenta acceder a la pantalla de confirmación de reserva
        $responseConfirmar = $this->get(route('reservas.confirmar', ['paquete_id' => $this->paquete->id]));
        $responseConfirmar->assertRedirect(route('login'));

        // 3. Invitado intenta enviar la reserva directamente por POST
        $responsePost = $this->post(route('reservas.store'), [
            'nombre' => 'Carlos Viajero',
            'email' => 'carlos@example.com',
            'documento' => '78945612',
            'paquete_id' => $this->paquete->id,
            'fecha_viaje' => now()->addDays(20)->format('Y-m-d'),
            'num_pasajeros' => 2,
            'total' => 3600.00,
        ]);
        $responsePost->assertRedirect(route('login'));
    }

    public function test_2_cliente_inicia_sesion_crea_reserva_y_se_guarda_en_base_de_datos(): void
    {
        // 1. Cliente se autentica
        $this->actingAs($this->cliente);

        // 2. Cliente accede al formulario de confirmación con datos pre-cargados
        $responseConfirmar = $this->get(route('reservas.confirmar', [
            'paquete_id' => $this->paquete->id,
            'adults' => 2,
            'asientos' => '12A,12B',
        ]));
        $responseConfirmar->assertStatus(200);
        $responseConfirmar->assertSee('Descubre Tokio y Kioto');
        $responseConfirmar->assertSee('Carlos Viajero');

        // 3. Cliente confirma y guarda la reserva
        $fechaViaje = now()->addDays(15)->format('Y-m-d');
        $responseStore = $this->post(route('reservas.store'), [
            'nombre' => 'Carlos Viajero',
            'email' => 'carlos@example.com',
            'documento' => '78945612',
            'paquete_id' => $this->paquete->id,
            'fecha_viaje' => $fechaViaje,
            'num_pasajeros' => 2,
            'total' => 3600.00,
            'asientos' => '12A,12B',
        ]);

        // Debe redirigir al checkout/pasarela de pagos
        $reserva = Reserva::where('user_id', $this->cliente->id)->first();
        $this->assertNotNull($reserva);
        $responseStore->assertRedirect(route('pagos.show', $reserva->id));

        // 4. Verificación en la base de datos (MySQL / testing DB)
        $this->assertDatabaseHas('reservas', [
            'id' => $reserva->id,
            'user_id' => $this->cliente->id,
            'paquete_id' => $this->paquete->id,
            'num_pasajeros' => 2,
            'total' => 3600.00,
            'estado' => 'pendiente',
        ]);

        // Verificación de pasajeros y asientos asignados
        $this->assertDatabaseHas('reserva_pasajeros', [
            'reserva_id' => $reserva->id,
            'nombre' => 'Carlos Viajero',
            'documento' => '78945612',
            'asiento' => '12A',
        ]);
        $this->assertDatabaseHas('reserva_pasajeros', [
            'reserva_id' => $reserva->id,
            'asiento' => '12B',
        ]);
    }

    public function test_3_reserva_registrada_aparece_en_el_dashboard_del_cliente(): void
    {
        // 1. Crear reserva para el cliente
        $reserva = Reserva::create([
            'user_id' => $this->cliente->id,
            'paquete_id' => $this->paquete->id,
            'fecha_viaje' => now()->addDays(10),
            'num_pasajeros' => 2,
            'total' => 3600.00,
            'estado' => 'pendiente',
        ]);

        // 2. Cliente ingresa a su Dashboard
        $response = $this->actingAs($this->cliente)->get(route('dashboard'));
        $response->assertStatus(200);

        // Verifica que la reserva figure en la vista
        $response->assertSee('Descubre Tokio y Kioto');
        $response->assertSee('US$ 3,600.00');
        $response->assertSee('aprox. S/');
        $response->assertSee('Pagar Reserva');
    }

    public function test_4_reserva_registrada_se_guarda_y_visualiza_en_el_dashboard_del_admin(): void
    {
        // 1. Crear reserva del cliente en la BD
        $reserva = Reserva::create([
            'user_id' => $this->cliente->id,
            'paquete_id' => $this->paquete->id,
            'fecha_viaje' => now()->addDays(20),
            'num_pasajeros' => 2,
            'total' => 3600.00,
            'estado' => 'pendiente',
        ]);

        // 2. Administrador accede al panel administrativo (/admin)
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);

        // Verifica que el admin vea la reserva en la tabla de Últimas Reservas
        $response->assertSee('#' . $reserva->id);
        $response->assertSee('Carlos Viajero');
        $response->assertSee('carlos@example.com');
        $response->assertSee('Descubre Tokio y Kioto');
        $response->assertSee('US$ 3,600.00');
        $response->assertSee('aprox. S/');
    }

    public function test_5_administrador_tambien_puede_generar_reserva_y_se_guarda_exitosamente(): void
    {
        // Administrador realizando reserva en el sistema
        $this->actingAs($this->admin);

        $responseStore = $this->post(route('reservas.store'), [
            'nombre' => 'Administrador WT',
            'email' => 'admin@worldtravel.com',
            'documento' => '10203040',
            'paquete_id' => $this->paquete->id,
            'fecha_viaje' => now()->addDays(30)->format('Y-m-d'),
            'num_pasajeros' => 1,
            'total' => 1800.00,
            'asientos' => '4A',
        ]);

        $reserva = Reserva::where('user_id', $this->admin->id)->first();
        $this->assertNotNull($reserva);

        // Redirige al detalle de la reserva en el panel admin con mensaje flash de éxito
        $responseStore->assertRedirect(route('admin.reservas.show', $reserva->id));
        $responseStore->assertSessionHas('success');

        // Se guardó en la base de datos
        $this->assertDatabaseHas('reservas', [
            'id' => $reserva->id,
            'user_id' => $this->admin->id,
            'paquete_id' => $this->paquete->id,
            'total' => 1800.00,
        ]);
    }

    public function test_6_detalles_de_la_reserva_muestra_el_numero_de_asiento_asignado_a_cada_pasajero(): void
    {
        $reserva = Reserva::create([
            'user_id' => $this->cliente->id,
            'paquete_id' => $this->paquete->id,
            'fecha_viaje' => now()->addDays(20),
            'num_pasajeros' => 2,
            'total' => 3600.00,
            'estado' => 'confirmada',
        ]);

        \App\Models\ReservaPasajero::create([
            'reserva_id' => $reserva->id,
            'nombre' => 'Carlos Titular',
            'documento' => '78945612',
            'asiento' => '18A',
        ]);

        \App\Models\ReservaPasajero::create([
            'reserva_id' => $reserva->id,
            'nombre' => 'Acompañante Dos',
            'documento' => '87654321',
            'asiento' => '18B',
        ]);

        // El administrador consulta los detalles de la reserva #id
        $response = $this->actingAs($this->admin)->get(route('admin.reservas.show', $reserva->id));
        $response->assertStatus(200);

        // Verifica que se muestre el número de asiento en la información y en los pasajeros
        $response->assertSee('Asientos asignados');
        $response->assertSee('18A, 18B');
        $response->assertSee('Asiento 18A');
        $response->assertSee('Asiento 18B');
        $response->assertSee('Carlos Titular');
        $response->assertSee('Acompañante Dos');
    }

    public function test_7_reserva_sin_asientos_explicitamente_elegidos_recibe_asientos_auto_asignados(): void
    {
        $this->actingAs($this->cliente);

        $responseStore = $this->post(route('reservas.store'), [
            'nombre' => 'Viajero Sin Asiento Manual',
            'email' => 'carlos@example.com',
            'documento' => '11223344',
            'paquete_id' => $this->paquete->id,
            'fecha_viaje' => now()->addDays(15)->format('Y-m-d'),
            'num_pasajeros' => 2,
            'total' => 3600.00,
            // Asientos omitidos o vacíos
            'asientos' => '',
        ]);

        $reserva = Reserva::where('user_id', $this->cliente->id)->latest('id')->first();
        $this->assertNotNull($reserva);

        $pasajeros = \App\Models\ReservaPasajero::where('reserva_id', $reserva->id)->get();
        $this->assertCount(2, $pasajeros);

        // Cada pasajero debe tener un asiento real asignado automáticamente (no nulo ni vacío)
        foreach ($pasajeros as $pasajero) {
            $this->assertNotNull($pasajero->asiento);
            $this->assertNotEmpty($pasajero->asiento);
        }
    }
}

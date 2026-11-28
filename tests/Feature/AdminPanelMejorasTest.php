<?php

namespace Tests\Feature;

use App\Models\Destino;
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

class AdminPanelMejorasTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DestinosSeeder::class);
        $this->seed(VueloSeeder::class);
        $this->seed(PaquetesOfertasSeeder::class);
        $this->seed(AlojamientoSeeder::class);
        $this->seed(ReservaSeeder::class);

        $this->admin = User::firstOrCreate(
            ['email' => 'admin_mejoras@worldtravel.com'],
            [
                'name' => 'Admin Mejoras',
                'password' => bcrypt('password123'),
                'rol' => 'admin',
            ]
        );
    }

    public function test_buscador_y_estadisticas_de_paquetes_usan_datos_reales(): void
    {
        $paquete = Paquete::firstOrFail();
        $otroPaquete = Paquete::where('id', '!=', $paquete->id)->firstOrFail();

        $paquete->update([
            'titulo' => 'Paquete Único Prueba',
            'activo' => false,
            'destacado' => true,
        ]);

        $index = $this->actingAs($this->admin)
            ->get(route('admin.paquetes.index'));

        $index->assertOk()
            ->assertSee('Total paquetes')
            ->assertSee('Destacados')
            ->assertSee((string) Paquete::count());

        $busqueda = $this->actingAs($this->admin)
            ->get(route('admin.paquetes.index', ['q' => 'Paquete Único Prueba']));

        $busqueda->assertOk()
            ->assertSee('Paquete Único Prueba')
            ->assertDontSee($otroPaquete->titulo);

        $filtros = $this->actingAs($this->admin)
            ->get(route('admin.paquetes.index', [
                'estado' => 'inactivo',
                'destacado' => '1',
            ]));

        $filtros->assertOk()
            ->assertSee('Paquete Único Prueba')
            ->assertDontSee($otroPaquete->titulo);

        $sinResultados = $this->actingAs($this->admin)
            ->get(route('admin.paquetes.index', ['q' => 'NoExistePaquete123']));

        $sinResultados->assertOk()
            ->assertDontSee('Paquete Único Prueba');
    }

    public function test_registro_de_destino_guarda_todos_los_campos_y_aparece_en_el_buscar(): void
    {
        $formulario = $this->actingAs($this->admin)
            ->get(route('admin.destinos.create'));

        $formulario->assertOk()
            ->assertSee('name="codigo_iata"', false)
            ->assertSee('name="aeropuerto"', false)
            ->assertSee('name="nombre"', false)
            ->assertSee('name="pais"', false)
            ->assertSee('name="descripcion"', false)
            ->assertSee('name="imagen"', false);

        $respuesta = $this->actingAs($this->admin)
            ->post(route('admin.destinos.store'), [
                'codigo_iata' => 'zzq',
                'aeropuerto' => 'Aeropuerto Internacional de Prueba',
                'nombre' => 'Ciudad Prueba IATA',
                'pais' => 'País Prueba',
                'descripcion' => 'Destino creado para validar el formulario completo.',
                'imagen' => 'ciudad-prueba.jpg',
            ]);

        $respuesta->assertRedirect(route('admin.destinos.index'));

        $destino = Destino::where('nombre', 'Ciudad Prueba IATA')->firstOrFail();

        $this->assertSame('ZZQ', $destino->codigo_iata);
        $this->assertSame('Aeropuerto Internacional de Prueba', $destino->aeropuerto);
        $this->assertSame('País Prueba', $destino->pais);
        $this->assertSame('ciudad-prueba.jpg', $destino->imagen);

        $duplicado = $this->actingAs($this->admin)
            ->post(route('admin.destinos.store'), [
                'codigo_iata' => 'ZZQ',
                'nombre' => 'Otra Ciudad Prueba',
                'pais' => 'País Prueba',
            ]);

        $duplicado->assertSessionHasErrors('codigo_iata');

        $inicio = $this->get(route('inicio'));
        $inicio->assertOk()
            ->assertSee('Ciudad Prueba IATA')
            ->assertSee('ZZQ');

        $buscador = $this->get(route('search', [
            'origin' => 'ZZQ',
            'destination' => 'LIM',
        ]));

        $buscador->assertOk()
            ->assertSee('Ciudad Prueba IATA')
            ->assertSee('ZZQ');
    }

    public function test_reservas_se_clasifican_en_cuatro_subsecciones_independientes(): void
    {
        $servicios = [
            'vuelo' => 'Vuelos',
            'alojamiento' => 'Alojamientos',
            'paquete' => 'Paquetes',
            'oferta' => 'Ofertas',
        ];

        $clientes = [];

        foreach ($servicios as $tipo => $plural) {
            $nombreCliente = 'Cliente Único '.ucfirst($tipo);

            $clientes[$tipo] = $nombreCliente;

            $usuario = User::create([
                'name' => $nombreCliente,
                'email' => 'cliente_'.$tipo.'@prueba.test',
                'password' => bcrypt('password123'),
                'rol' => 'cliente',
            ]);

            Reserva::create([
                'user_id' => $usuario->id,
                'tipo_servicio' => $tipo,
                'fecha_viaje' => now()->addDays(15)->toDateString(),
                'num_pasajeros' => 1,
                'total' => 100,
                'estado' => 'pendiente',
                'detalle_servicio' => 'Reserva de prueba '.$tipo,
            ]);
        }

        $resumen = $this->actingAs($this->admin)
            ->get(route('admin.reservas.index'));

        $resumen->assertOk()
            ->assertSee('Gestión de reservas');

        foreach ($servicios as $plural) {
            $resumen->assertSee('Reservas de '.$plural);
        }

        foreach ($servicios as $tipo => $plural) {
            $respuesta = $this->actingAs($this->admin)
                ->get(route('admin.reservas.'.$tipo.'s'));

            $respuesta->assertOk()
                ->assertSee('Reservas de '.$plural)
                ->assertSee('Lista de reservas de '.$plural)
                ->assertSee($clientes[$tipo]);

            foreach ($clientes as $otroTipo => $nombreCliente) {
                if ($otroTipo !== $tipo) {
                    $respuesta->assertDontSee($nombreCliente);
                }
            }
        }

        $filtradas = $this->actingAs($this->admin)
            ->get(route('admin.reservas.vuelos', ['estado' => 'confirmada']));

        $filtradas->assertOk()
            ->assertDontSee($clientes['vuelo']);
    }
}

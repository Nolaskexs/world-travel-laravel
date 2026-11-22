<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchFlightTest extends TestCase
{
    use RefreshDatabase;

    public function test_busqueda_de_tokio_a_arequipa_genera_rutas_con_nombres_e_iata_correctos(): void
    {
        $response = $this->get(route('search', [
            'origin' => 'Tokio',
            'destination' => 'Arequipa',
            'trip' => 'round',
            'start' => '2026-12-17',
            'end' => '2026-12-24',
            'adults' => 3,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Tokio');
        $response->assertSee('Arequipa');

        // Petición JSON para validar los datos pasados al cliente
        $jsonResponse = $this->getJson(route('search', [
            'origin' => 'Tokio',
            'destination' => 'Arequipa',
            'trip' => 'round',
            'start' => '2026-12-17',
            'end' => '2026-12-24',
            'adults' => 3,
        ]));
        $jsonResponse->assertStatus(200);

        $data = $jsonResponse->json();
        $this->assertEquals('Tokio', $data['params']['origin']);
        $this->assertEquals('HND', $data['params']['originIata']);
        $this->assertEquals('Arequipa', $data['params']['destination']);
        $this->assertEquals('AQP', $data['params']['destinationIata']);

        // Verificar que los vuelos generados tienen aerolíneas internacionales realistas y duraciones adecuadas para vuelos transpacíficos
        $vuelos = $data['vuelos'];
        $this->assertNotEmpty($vuelos);
        $this->assertEquals('Tokio', $vuelos[0]['origen']['nombre']);
        $this->assertEquals('HND', $vuelos[0]['origen']['codigo_iata']);
        $this->assertEquals('Arequipa', $vuelos[0]['destino']['nombre']);
        $this->assertEquals('AQP', $vuelos[0]['destino']['codigo_iata']);

        // La duración para Asia - Sudamérica debe ser superior a 20 horas (1200 minutos)
        $this->assertGreaterThan(1200, $vuelos[0]['duracion_minutos']);

        // Validar que aerolíneas reconocidas están presentes
        $aerolineas = array_column($vuelos, 'aerolinea');
        $this->assertTrue(
            in_array('All Nippon Airways', $aerolineas) ||
            in_array('United Airlines', $aerolineas) ||
            in_array('LATAM Airlines', $aerolineas)
        );
    }

    public function test_busqueda_acepta_alias_y_codigos_iata_en_minuscula(): void
    {
        $jsonResponse = $this->getJson(route('search', ['origin' => 'tokyo', 'destination' => 'aqp']));
        $jsonResponse->assertStatus(200);

        $data = $jsonResponse->json();
        $this->assertEquals('Tokio', $data['params']['origin']);
        $this->assertEquals('HND', $data['params']['originIata']);
        $this->assertEquals('Arequipa', $data['params']['destination']);
        $this->assertEquals('AQP', $data['params']['destinationIata']);
    }

    public function test_busqueda_sin_datos_muestra_aviso_y_no_genera_vuelos_ficticios_de_bangkok(): void
    {
        $response = $this->get(route('search'));
        $response->assertStatus(200);
        $response->assertSee('Completa tu búsqueda');
        $response->assertSee('Por favor, ingresa una ciudad de origen y una ciudad de destino');
        $response->assertDontSee('Multidestino');

        $jsonResponse = $this->getJson(route('search'));
        $jsonResponse->assertStatus(200);

        $data = $jsonResponse->json();
        $this->assertTrue($data['params']['busquedaIncompleta']);
        $this->assertNotEmpty($data['params']['avisoBusqueda']);
        $this->assertEmpty($data['vuelos']);
    }

    public function test_no_existe_opcion_multidestino_en_inicio(): void
    {
        $response = $this->get(route('inicio'));
        $response->assertStatus(200);
        $response->assertDontSee('Multidestino');
    }
}

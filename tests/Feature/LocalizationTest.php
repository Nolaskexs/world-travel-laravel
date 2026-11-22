<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class LocalizationTest extends TestCase
{
    use RefreshDatabase;
    public function test_la_aplicacion_usa_el_idioma_espanol_por_defecto(): void
    {
        $this->assertEquals('es', App::getLocale());
    }

    public function test_traducciones_de_autenticacion_en_espanol(): void
    {
        $this->assertEquals(
            'Estas credenciales no coinciden con nuestros registros.',
            Lang::get('auth.failed')
        );

        $this->assertEquals(
            'La contraseña proporcionada es incorrecta.',
            Lang::get('auth.password')
        );
    }

    public function test_traducciones_de_validacion_en_espanol(): void
    {
        $mensajeRequerido = Lang::get('validation.required', ['attribute' => 'correo electrónico']);
        $this->assertEquals('El campo correo electrónico es obligatorio.', $mensajeRequerido);

        $mensajeEmail = Lang::get('validation.email', ['attribute' => 'correo electrónico']);
        $this->assertEquals('El campo correo electrónico debe ser una dirección de correo válida.', $mensajeEmail);
    }

    public function test_traducciones_de_contrasenas_y_paginacion_en_espanol(): void
    {
        $this->assertEquals(
            '¡Tu contraseña ha sido restablecida!',
            Lang::get('passwords.reset')
        );

        $this->assertEquals(
            '&laquo; Anterior',
            Lang::get('pagination.previous')
        );

        $this->assertEquals(
            'Siguiente &raquo;',
            Lang::get('pagination.next')
        );
    }

    public function test_errores_de_validacion_web_se_generan_en_espanol(): void
    {
        // Enviar formulario de login vacío y verificar que los errores están en español
        $response = $this->post('/login', []);

        $response->assertSessionHasErrors([
            'email' => 'El campo correo electrónico es obligatorio.'
        ]);
    }

    public function test_login_con_credenciales_invalidas_muestra_error_en_espanol(): void
    {
        $response = $this->post('/login', [
            'email' => 'noexiste@worldtravel.com',
            'password' => 'claveincorrecta123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Estas credenciales no coinciden con nuestros registros.'
        ]);
    }
}

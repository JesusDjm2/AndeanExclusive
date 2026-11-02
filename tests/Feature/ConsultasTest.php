<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Programa;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ConsultasTest extends TestCase
{
    public static function formularios(): array
    {
        return [
            'mensaje01' => ['/mensaje01', 'en', 'flash', ['name' => 'Elena', 'tour' => 'Machu Picchu - 3 days', 'adultos' => 2, 'child' => 1, 'da' => '2030-05-10', 'mensaje' => 'Hola']],
            'bookindex' => ['/bookindex', 'en', 'flash', ['da' => '2030-05-10', 'do' => '2030-05-16', 'adultos' => 2]],
            'bookindex2' => ['/bookindex2', 'en', 'contact_flash', ['nombre' => 'Elena', 'telefono' => '+34 600', 'mensaje' => 'Hola']],
            'mensaje02' => ['/mensaje02', 'es', 'flash', ['name' => 'Elena', 'tour' => 'Cusco', 'mensaje' => 'Hola']],
            'book-castellano' => ['/book-castellano', 'es', 'flash', ['da' => '2030-05-10', 'adultos' => 2]],
            'bookindex2castellano' => ['/bookindex2castellano', 'es', 'contact_flash', ['nombre' => 'Elena', 'telefono' => '+34 600']],
        ];
    }

    #[DataProvider('formularios')]
    public function test_cada_formulario_guarda_la_consulta_avisa_y_envia_correo(string $ruta, string $idioma, string $clave, array $datos): void
    {
        $this->comoVisitaWeb()->from('/')->post($ruta, $datos + ['email' => 'elena@example.com'])->assertRedirect('/')->assertSessionHas($clave);

        $consulta = Consulta::sole();
        $this->assertSame('elena@example.com', $consulta->email);
        $this->assertSame($idioma, $consulta->idioma);
        $this->assertSame('nueva', $consulta->estado);
        $this->assertTrue(Aviso::where('area', 'operaciones')->where('tipo', 'consulta_nueva')->exists());
    }

    public function test_si_el_correo_falla_la_consulta_queda_guardada(): void
    {
        config(['mail.default' => 'no-existe']);

        $this->comoVisitaWeb()->from('/')->post('/bookindex2', ['email' => 'elena@example.com', 'nombre' => 'Elena'])->assertSessionHas('contact_flash');

        $this->assertSame(1, Consulta::count());
    }

    public function test_rechaza_datos_invalidos_y_robots(): void
    {
        $this->comoVisitaWeb()->from('/')->post('/bookindex2', ['email' => 'no-es-correo'])->assertSessionHasErrors('email');
        $this->from('/')->post('/bookindex2', ['email' => 'bot@example.com', 'personas' => 'x'])->assertSessionHas('contact_flash');

        $this->assertSame(0, Consulta::count());
    }

    public function test_los_formularios_limitan_los_envios_repetidos(): void
    {
        $this->comoVisitaWeb();
        foreach (range(1, 6) as $i) {
            $this->from('/')->post('/bookindex2', ['email' => "a{$i}@example.com"])->assertRedirect();
        }

        $this->from('/')->post('/bookindex2', ['email' => 'a7@example.com'])->assertTooManyRequests();
    }

    public function test_de_la_consulta_a_la_cotizacion_y_a_ganada(): void
    {
        $ana = $this->usuario('operaciones');
        $consulta = Consulta::create(['nombre' => 'Elena Rivas', 'email' => 'elena@example.com', 'idioma' => 'es', 'tour' => 'Cusco en familia', 'fecha_inicio' => '2030-05-10', 'fecha_fin' => '2030-05-16']);

        $this->actingAs($ana)->post("/consultas/{$consulta->id}/cotizar")->assertRedirectContains('/programas/create');
        $cliente = Cliente::where('email', 'elena@example.com')->sole();

        $this->get("/programas/create?cliente_id={$cliente->id}&consulta={$consulta->id}")->assertOk()->assertSee('Cusco en familia')->assertSee('name="consulta_id"', false);

        $this->post('/programas', [
            'nombre' => 'Cusco en familia', 'estado' => 'cotizacion', 'lang' => 'es', 'cliente_modo' => 'existente', 'cliente_id' => $cliente->id,
            'inicio' => '2030-05-10', 'fin' => '2030-05-16', 'consulta_id' => $consulta->id,
        ])->assertSessionHasNoErrors();

        $programa = Programa::where('nombre', 'Cusco en familia')->sole();
        $this->assertSame('cotizada', $consulta->fresh()->estado);
        $this->assertSame($programa->id, $consulta->fresh()->programa_id);
        $this->assertSame($ana->id, $consulta->fresh()->asignado_id);

        $this->put("/programas/{$programa->id}", $this->formulario($programa, ['estado' => 'confirmado']))->assertSessionHasNoErrors();
        $this->assertSame('ganada', $consulta->fresh()->estado);

        $this->put("/programas/{$programa->id}", $this->formulario($programa->fresh(), ['estado' => 'cancelado']))->assertSessionHasNoErrors();
        $this->assertSame('perdida', $consulta->fresh()->estado);
    }

    public function test_el_mismo_correo_no_crea_un_cliente_repetido(): void
    {
        $cliente = Cliente::create(['tipo' => 'directo', 'nombre' => 'Elena', 'email' => 'elena@example.com']);
        $consulta = Consulta::create(['nombre' => 'Elena R.', 'email' => 'elena@example.com']);

        $this->actingAs($this->usuario('operaciones'))->post("/consultas/{$consulta->id}/cotizar");

        $this->assertSame(1, Cliente::count());
        $this->assertSame($cliente->id, $consulta->fresh()->cliente_id);
    }

    public function test_seguimiento_y_perdida_con_motivo(): void
    {
        $ana = $this->usuario('operaciones');
        $consulta = Consulta::create(['nombre' => 'Elena', 'email' => 'e@example.com']);
        $this->actingAs($ana);
        \App\Support\Avisos::enviar('operaciones', 'consulta_nueva', 'x', null, 'y', 'consultas.show', ['consulta' => $consulta->id], null, "consulta:{$consulta->id}");

        $this->put("/consultas/{$consulta->id}", ['estado' => 'perdida'])->assertSessionHasErrors('motivo_perdida');
        $this->put("/consultas/{$consulta->id}", ['estado' => 'en_gestion', 'asignado_id' => $ana->id, 'notas' => 'Le escribí'])->assertSessionHasNoErrors();
        $this->assertNotNull($consulta->fresh()->atendida_at);
        $this->assertTrue(Aviso::where('clave', "consulta:{$consulta->id}")->whereNotNull('resuelto_at')->exists(), 'Atenderla resuelve el aviso');

        $this->put("/consultas/{$consulta->id}", ['estado' => 'perdida', 'motivo_perdida' => 'precio'])->assertSessionHasNoErrors();
        $this->get('/consultas?bandeja=perdidas')->assertOk()->assertSee('Elena')->assertSee('Precio');
    }

    public function test_consulta_manual_y_permisos(): void
    {
        $consulta = Consulta::create(['nombre' => 'Elena', 'email' => 'e@example.com']);

        foreach (['reservas', 'contabilidad'] as $rol) {
            $this->actingAs($this->usuario($rol));
            $this->get('/consultas')->assertOk()->assertDontSee('Nueva consulta');
            $this->get("/consultas/{$consulta->id}")->assertOk()->assertDontSee('Crear cotización');
            $this->post('/consultas', ['nombre' => 'x'])->assertForbidden();
            $this->put("/consultas/{$consulta->id}", ['estado' => 'perdida'])->assertForbidden();
            $this->post("/consultas/{$consulta->id}/cotizar")->assertForbidden();
        }

        $ana = $this->usuario('operaciones');
        $this->actingAs($ana)->get('/consultas/create')->assertOk();
        $this->post('/consultas', ['nombre' => 'Por WhatsApp', 'origen' => 'whatsapp', 'idioma' => 'es'])->assertSessionHasNoErrors();
        $manual = Consulta::where('nombre', 'Por WhatsApp')->sole();
        $this->assertSame('en_gestion', $manual->estado);
        $this->assertSame($ana->id, $manual->asignado_id);
    }

    public function test_no_inyecta_html_del_mensaje_en_la_cotizacion(): void
    {
        $consulta = Consulta::create(['nombre' => 'E', 'mensaje' => '<script>alert(1)</script>']);
        $this->actingAs($this->usuario('operaciones'));

        $this->get("/consultas/{$consulta->id}")->assertOk()->assertDontSee('<script>alert(1)</script>', false);
        $this->get("/programas/create?consulta={$consulta->id}")->assertOk()->assertDontSee('<script>alert(1)</script>', false);
    }
}

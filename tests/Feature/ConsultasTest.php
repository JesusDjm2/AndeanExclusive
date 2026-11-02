<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Mail\ConsultaRecibidaMailable;
use App\Models\Programa;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ConsultasTest extends TestCase
{
    /** Lo que la página incluye en cada formulario: la hora en que se cargó. */
    private function sello(int $hace = 60): array
    {
        return ['_sello' => Crypt::encryptString((string) (now()->timestamp - $hace))];
    }

    public static function formularios(): array
    {
        return [
            'tour en inglés' => ['/mensaje01', 'en', 'tour', '#book', ['nombre' => 'Elena', 'tour' => 'Machu Picchu - 3 days', 'adultos' => 2, 'child' => 1, 'da' => '2030-05-10', 'mensaje' => 'Hola']],
            'portada en inglés' => ['/bookindex', 'en', 'rapida', '#consulta-rapida', ['da' => '2030-05-10', 'do' => '2030-05-16', 'adultos' => 2]],
            'contacto en inglés' => ['/bookindex2', 'en', 'contacto', '', ['nombre' => 'Elena', 'telefono' => '+34 600', 'mensaje' => 'Hola']],
            'tour en español' => ['/mensaje02', 'es', 'tour', '#book', ['nombre' => 'Elena', 'tour' => 'Cusco', 'mensaje' => 'Hola']],
            'portada en español' => ['/book-castellano', 'es', 'rapida', '#consulta-rapida', ['da' => '2030-05-10', 'adultos' => 2]],
            'contacto en español' => ['/bookindex2castellano', 'es', 'contacto', '', ['nombre' => 'Elena', 'telefono' => '+34 600']],
        ];
    }

    #[DataProvider('formularios')]
    public function test_cada_formulario_guarda_avisa_y_envia_el_correo_al_destinatario(string $ruta, string $idioma, string $form, string $ancla, array $datos): void
    {
        Mail::fake();
        config(['mail.consultas_destino' => 'pruebas@example.com']);

        $this->comoVisitaWeb()->from('http://localhost/pagina')->post($ruta, $datos + $this->sello() + ['email' => 'elena@example.com'])
            ->assertRedirect('http://localhost/pagina' . $ancla)->assertSessionHas('form_enviado', $form);

        $consulta = Consulta::sole();
        $this->assertSame('elena@example.com', $consulta->email);
        $this->assertSame($idioma, $consulta->idioma);
        $this->assertSame('nueva', $consulta->estado);
        $this->assertTrue(Aviso::where('area', 'operaciones')->where('tipo', 'consulta_nueva')->exists());
        Mail::assertSent(ConsultaRecibidaMailable::class, fn ($m) => $m->hasTo('pruebas@example.com') && $m->hasReplyTo('elena@example.com'));
    }

    public function test_el_correo_muestra_los_datos_sin_interpretar_html(): void
    {
        $consulta = Consulta::create(['nombre' => 'Elena <b>R</b>', 'email' => 'e@example.com', 'tour' => 'Cusco', 'adultos' => 2, 'mensaje' => "Hola\n<script>alert(1)</script>"]);

        $html = (new ConsultaRecibidaMailable($consulta))->render();

        $this->assertStringContainsString('Cusco', $html);
        $this->assertStringNotContainsString('<script>alert(1)', $html);
        $this->assertStringNotContainsString('<b>R</b>', $html);
        $this->assertStringContainsString('consultas/' . $consulta->id, $html);
    }

    public function test_si_el_correo_falla_la_consulta_queda_guardada(): void
    {
        config(['mail.default' => 'no-existe']);

        $this->comoVisitaWeb()->from('http://localhost/x')->post('/bookindex2', ['email' => 'elena@example.com', 'nombre' => 'Elena'] + $this->sello())->assertSessionHas('form_enviado', 'contacto');

        $this->assertSame(1, Consulta::count());
    }

    public function test_los_errores_vuelven_al_formulario_correcto_y_conservan_lo_escrito(): void
    {
        $this->comoVisitaWeb()->from('http://localhost/x')->post('/mensaje02', ['nombre' => 'Elena', 'email' => 'no-es-correo', 'mensaje' => 'Mi viaje'] + $this->sello())
            ->assertRedirect('http://localhost/x#book')->assertSessionHasErrors(['email' => 'Revisa tu correo: parece incompleto.'])
            ->assertSessionHas('form_activo', 'tour')->assertSessionHasInput('mensaje', 'Mi viaje');

        $this->from('http://localhost/x')->post('/bookindex', ['email' => 'a@example.com', 'adultos' => 2, 'da' => '2030-05-10', 'do' => '2030-05-01'] + $this->sello())
            ->assertSessionHasErrors(['do' => 'Departure cannot be before arrival.']);
        $this->from('http://localhost/x')->post('/bookindex', ['email' => 'a@example.com'] + $this->sello())->assertSessionHasErrors(['adultos' => 'Tell us how many adults are traveling.']);

        $this->assertSame(0, Consulta::count());
    }

    public static function robots(): array
    {
        return [
            'llena el campo trampa' => [['sitio_web' => 'x', 'nombre' => 'Bot']],
            'envía en menos de 2 segundos' => [['nombre' => 'Bot', '_sello' => null]],
            'sin hora de carga' => [['nombre' => 'Bot', '_sello' => '']],
            'hora de carga falsa' => [['nombre' => 'Bot', '_sello' => 'abc']],
        ];
    }

    #[DataProvider('robots')]
    public function test_los_robots_creen_que_enviaron_pero_no_se_guarda_nada(array $datos): void
    {
        Mail::fake();
        if (array_key_exists('_sello', $datos) && $datos['_sello'] === null) {
            $datos = $this->sello(0) + $datos;
        } elseif (! array_key_exists('_sello', $datos)) {
            $datos += $this->sello();
        }

        $this->comoVisitaWeb()->from('http://localhost/x')->post('/bookindex2', $datos + ['email' => 'bot@example.com'])->assertSessionHas('form_enviado', 'contacto');

        $this->assertSame(0, Consulta::count());
        Mail::assertNothingSent();
    }

    public function test_los_formularios_limitan_los_envios_repetidos(): void
    {
        $this->comoVisitaWeb();
        foreach (range(1, 6) as $i) {
            $this->from('http://localhost/x')->post('/bookindex2', ['email' => "a{$i}@example.com", 'nombre' => 'A'] + $this->sello())->assertRedirect();
        }

        $this->from('http://localhost/x')->post('/bookindex2', ['email' => 'a7@example.com', 'nombre' => 'A'] + $this->sello())->assertTooManyRequests();
    }

    public function test_cada_pagina_muestra_su_formulario_accesible_y_su_confirmacion(): void
    {
        foreach (['/', '/turismo-Peru'] as $portada) {
            $this->get($portada)->assertOk()->assertSee('id="consulta-rapida"', false)->assertSee('for="rapida-email"', false)->assertSee('name="_sello"', false)->assertDontSee('data-captcha', false);
        }
        $this->withSession(['form_enviado' => 'rapida'])->get('/')->assertSee('We received your request!')->assertDontSee('action="' . route('bookindex') . '"', false);
        $this->withSession(['form_enviado' => 'rapida'])->get('/turismo-Peru')->assertSee('¡Recibimos tu consulta!');

        $this->withSession(['form_enviado' => 'contacto'])->get('/about')->assertSee('data-auto-open', false)->assertSee('We received your request!');
        $this->flushSession()->get('/about')->assertDontSee('data-auto-open', false);

        $this->withSession(['form_activo' => 'contacto', 'errors' => (new ViewErrorBag)->put('default', new MessageBag(['nombre' => ['Please enter your name.']]))])
            ->get('/about')->assertSee('data-auto-open', false)->assertSee('Please enter your name.')->assertSee('aria-invalid="true"', false);
    }

    public function test_la_pagina_de_un_tour_confirma_el_envio(): void
    {
        $tour = \App\Models\Tour::query()->first() ?? null;
        if (! $tour) {
            $this->assertStringContainsString("session('form_enviado')", file_get_contents(resource_path('views/layouts/partials/public-booking-form.blade.php')));

            return;
        }
        $this->withSession(['form_enviado' => 'tour'])->get('/en/' . $tour->slug)->assertSee('We received your request!');
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

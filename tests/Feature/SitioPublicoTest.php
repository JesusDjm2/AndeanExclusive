<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\HtmlSeguro;
use Tests\TestCase;

class SitioPublicoTest extends TestCase
{
    public function test_cabeceras_de_seguridad(): void
    {
        $this->get('http://localhost/login')->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')->assertHeaderMissing('Strict-Transport-Security');

        $this->get('https://localhost/login')->assertHeader('Strict-Transport-Security', 'max-age=31536000');
    }

    public function test_el_enlace_del_cliente_no_se_indexa_ni_se_filtra(): void
    {
        $programa = $this->programa();

        $this->get("/itinerario/{$programa->token}")->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow')->assertHeader('Referrer-Policy', 'no-referrer');
        $this->get('/robots.txt')->assertSee('Disallow: /itinerario/');
    }

    public function test_un_sitio_de_pruebas_no_se_indexa(): void
    {
        config(['seo.indexar' => false]);

        $this->get('/robots.txt')->assertSee('Disallow: /')->assertDontSee('Sitemap:');
        $this->get('/terms-conditions-aet')->assertOk()->assertHeader('X-Robots-Tag', 'noindex, nofollow')->assertSee('content="noindex, nofollow"', false);
    }

    public function test_la_web_oficial_si_se_indexa(): void
    {
        $this->get('/robots.txt')->assertSee('Allow: /')->assertSee('Sitemap:');
        $this->get('/terms-conditions-aet')->assertOk()->assertHeaderMissing('X-Robots-Tag')->assertSee('content="index, follow', false);
    }

    public function test_cada_pagina_principal_tiene_lo_que_pide_un_buscador(): void
    {
        foreach (config('seo.pages') as $locale => $paginas) {
            foreach ($paginas as $clave => $pagina) {
                $this->assertLessThanOrEqual(60, mb_strlen($pagina['title']), "$locale.$clave: título largo");
                $this->assertLessThanOrEqual(160, mb_strlen($pagina['description'] ?? ''), "$locale.$clave: descripción larga");
            }
        }

        foreach (['/about', '/nosotros', '/faqs', '/preguntas-frecuentes'] as $ruta) {
            $h = (string) $this->get($ruta)->assertOk()->getContent();
            $this->assertSame(1, substr_count($h, '<h1'), "$ruta: un solo h1");
            $this->assertStringContainsString('rel="canonical" href="' . url($ruta) . '"', $h, $ruta);
            $this->assertStringContainsString('hreflang="x-default"', $h, $ruta);
            $this->assertStringContainsString('application/ld+json', $h, $ruta);
        }
    }

    public function test_pagina_no_encontrada_orienta_y_no_se_indexa(): void
    {
        $this->get('/no-existe-esta-pagina-xyz')->assertNotFound()->assertSee('We could not find this page')->assertSee('noindex');
        $this->get('/no-existe-esta-pagina-xyz', ['Accept-Language' => 'es-PE'])->assertNotFound()->assertSee('No encontramos esta página');
    }

    public function test_la_pagina_de_prueba_ya_no_existe(): void
    {
        $this->get('/mostrar')->assertNotFound();
    }

    public function test_el_panel_no_se_indexa(): void
    {
        $this->actingAs($this->usuario('operaciones'))->get('/home')->assertOk()->assertSee('noindex, nofollow', false);
    }

    public function test_el_contenido_del_editor_pierde_lo_que_ejecuta_codigo(): void
    {
        $sucio = '<p onclick="alert(1)">Hola <strong>mundo</strong></p><script>alert(2)</script>'
            . '<a href="javascript:alert(3)">x</a><a href=" java&#10;script:alert(4)">y</a><img src="foto.jpg" alt="Foto" onerror="alert(5)">'
            . '<iframe src="https://mal.example"></iframe><svg onload="alert(6)"></svg><div style="width:expression(alert(7))">z</div>';

        $limpio = HtmlSeguro::limpiar($sucio);

        foreach (['onclick', 'alert', 'script', 'onerror', 'iframe', 'svg', 'expression'] as $peligro) {
            $this->assertStringNotContainsStringIgnoringCase($peligro, $limpio);
        }
        $this->assertStringContainsString('<strong>mundo</strong>', $limpio);
        $this->assertStringContainsString('src="foto.jpg"', $limpio);
        $this->assertStringContainsString('alt="Foto"', $limpio);

        $this->assertSame('<p>Años, ñandú y Perú</p>', HtmlSeguro::limpiar('<p>Años, ñandú y Perú</p>'));
        $this->assertNull(HtmlSeguro::limpiar(null));
    }

    public function test_el_programa_guarda_la_presentacion_limpia(): void
    {
        $programa = $this->programa(['presentacion' => '<p>Hola</p><script>alert(1)</script>']);

        $this->assertSame('<p>Hola</p>', $programa->fresh()->presentacion);
    }

    public function test_nadie_elimina_su_propia_cuenta(): void
    {
        $admin = $this->usuario('administrador');

        $this->actingAs($admin)->delete("/users/{$admin->id}");

        $this->assertTrue(User::whereKey($admin->id)->exists());
    }
}

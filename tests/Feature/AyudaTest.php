<?php

namespace Tests\Feature;

use App\Support\Ayuda;
use App\Support\Permisos;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class AyudaTest extends TestCase
{
    public function test_cada_pantalla_del_panel_tiene_ayuda(): void
    {
        $ignoradas = ['avisos.resumen', 'avisos.abrir', 'ayuda.*', 'servicios.*', 'programas.pdf', 'programas.historial', 'programas.rooming', 'reportes.exportar', 'respaldos.descargar', 'paxs.pasaporte', 'password.*', 'verification.*'];
        $sinAyuda = collect(Route::getRoutes())
            ->filter(fn ($r) => $r->getName() && in_array('GET', $r->methods(), true) && in_array('auth', $r->gatherMiddleware(), true))
            ->map->getName()
            ->reject(fn ($n) => Str::is($ignoradas, $n))
            ->reject(fn ($n) => collect(Ayuda::TEMAS)->contains(fn ($t) => Str::is($t['rutas'], $n)))
            ->values();

        $this->assertSame([], $sinAyuda->all());
    }

    public function test_los_temas_apuntan_a_rutas_y_permisos_reales(): void
    {
        $rutas = collect(Route::getRoutes())->map->getName()->filter();

        foreach (Ayuda::TEMAS as $clave => $tema) {
            foreach ($tema['rutas'] as $patron) {
                $this->assertTrue($rutas->contains(fn ($n) => Str::is($patron, $n)), "$clave: $patron");
            }
            foreach (array_filter([$tema['permiso'] ?? null, $tema['sin_permiso'] ?? null]) as $permiso) {
                $this->assertArrayHasKey($permiso, Permisos::MATRIZ, $clave);
            }
            $this->assertArrayHasKey($tema['grupo'], Ayuda::GRUPOS, $clave);
            $this->assertNotEmpty($tema['pasos'], $clave);
        }
    }

    public function test_cada_area_ve_lo_suyo_y_no_lo_reservado(): void
    {
        $programa = $this->programa(['estado' => 'confirmado']);
        $ficha = "/programas/{$programa->id}/edit";

        $this->actingAs($this->usuario('reservas'))->get($ficha)->assertOk()
            ->assertSee('La ficha de un programa en modo consulta')->assertSee('Solo consulta')
            ->assertDontSee('Pulsa «Agregar pasajero»')->assertDontSee('Vigilar el margen');

        $this->actingAs($this->usuario('operaciones'))->get($ficha)->assertOk()
            ->assertSee('Pulsa «Agregar pasajero»')->assertDontSee('La ficha de un programa en modo consulta')->assertDontSee('el sistema propone lo que falta');

        $this->actingAs($this->usuario('contabilidad'))->get($ficha)->assertOk()
            ->assertSee('el sistema propone lo que falta')->assertSee('Vigilar el margen');

        $this->actingAs($this->usuario('reservas'))->get('/ayuda')->assertOk()->assertSee('Tu área')
            ->assertDontSee('Crear y administrar usuarios')->assertDontSee('Sitio web: tours y blog');

        $this->actingAs($this->usuario('administrador'))->get('/ayuda')->assertOk()->assertSee('Crear y administrar usuarios');
    }

    public function test_la_guia_pide_sesion(): void
    {
        $this->get('/ayuda')->assertRedirect('/login');
        $this->get('/ayuda/guia')->assertRedirect('/login');
        $this->assertFileDoesNotExist(public_path(Ayuda::GUIA));

        if (Ayuda::guiaDisponible()) {
            $this->actingAs($this->usuario('contabilidad'))->get('/ayuda/guia')->assertOk();
        }
    }
}

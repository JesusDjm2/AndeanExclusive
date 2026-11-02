<?php

namespace Tests\Feature;

use App\Models\Pax;
use App\Support\Respaldos;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SeguridadDeDatosTest extends TestCase
{
    private function pasajero(array $datos = []): Pax
    {
        return $this->programa()->paxs()->create($datos + ['nombre' => 'Elena', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES', 'pasaporte' => 'XE123456', 'condiciones_medicas' => 'Hipertensión']);
    }

    public function test_el_pasaporte_y_la_salud_se_guardan_cifrados(): void
    {
        $pax = $this->pasajero();
        $fila = DB::table('paxes')->where('id', $pax->id)->first();

        $this->assertStringNotContainsString('XE123456', $fila->pasaporte);
        $this->assertStringNotContainsString('Hipertensión', $fila->condiciones_medicas);
        $this->assertSame('XE123456', $pax->fresh()->pasaporte);
        $this->assertSame('Hipertensión', $pax->fresh()->condiciones_medicas);
    }

    public function test_el_itinerario_nunca_muestra_el_pasaporte_completo(): void
    {
        $pax = $this->pasajero();

        $url = '/itinerario/' . $pax->programa->token;
        $this->get($url)->assertOk()->assertDontSee('XE123456')->assertDontSee('•••456');
        $this->actingAs($this->usuario('operaciones'))->get($url)->assertOk()->assertDontSee('XE123456')->assertSee('•••456');
    }

    public function test_contabilidad_no_ve_pasaportes_y_quien_reserva_si(): void
    {
        $pax = $this->pasajero();
        $ficha = "/programas/{$pax->programa_id}/edit";

        $this->actingAs($this->usuario('contabilidad'));
        $this->get($ficha)->assertOk()->assertDontSee('XE123456')->assertSee('•••456');
        $this->get('/paxs')->assertOk()->assertDontSee('XE123456');
        $this->get("/paxs/{$pax->id}")->assertOk()->assertDontSee('XE123456');
        $this->get("/paxs/{$pax->id}/pasaporte")->assertForbidden();

        foreach (['reservas', 'operaciones', 'administrador'] as $rol) {
            $this->actingAs($this->usuario($rol))->get($ficha)->assertOk()->assertSee('XE123456');
        }
    }

    public function test_guardar_una_ficha_conserva_el_pasaporte(): void
    {
        $pax = $this->pasajero();
        $this->actingAs($this->usuario('operaciones'))->put("/programas/{$pax->programa_id}", $this->formulario($pax->programa))->assertSessionHasNoErrors();

        $this->assertSame('XE123456', $pax->fresh()->pasaporte);
    }

    private function entrar(string $rol)
    {
        $user = $this->usuario($rol);

        return [$user, $this->post('/login', ['email' => $user->email, 'password' => 'password'])];
    }

    public function test_sin_activarla_se_entra_directo(): void
    {
        [, $respuesta] = $this->entrar('administrador');

        $respuesta->assertRedirect('/home');
        $this->assertAuthenticated();
    }

    public function test_la_doble_verificacion_pide_el_codigo_del_correo(): void
    {
        config(['seguridad.doble_verificacion' => true]);
        Mail::fake();
        $codigo = null;
        Mail::shouldReceive('raw')->once()->andReturnUsing(function (string $texto) use (&$codigo) {
            preg_match('/(\d{6})/', $texto, $m);
            $codigo = $m[1];
        });

        [$user, $respuesta] = $this->entrar('administrador');
        $respuesta->assertRedirect('/verificacion');
        $this->assertGuest();
        $this->get('/home')->assertRedirect('/login');
        $this->get('/verificacion')->assertOk();

        $this->post('/verificacion', ['codigo' => '000000'])->assertSessionHasErrors('codigo');
        $this->assertGuest();

        $this->post('/verificacion', ['codigo' => $codigo])->assertRedirect('/home')->assertCookie('ae_equipo_confianza');
        $this->assertAuthenticatedAs($user);
    }

    public function test_tras_cinco_fallos_el_codigo_deja_de_valer(): void
    {
        config(['seguridad.doble_verificacion' => true]);
        $codigo = null;
        Mail::shouldReceive('raw')->andReturnUsing(function (string $texto) use (&$codigo) {
            preg_match('/(\d{6})/', $texto, $m);
            $codigo = $m[1];
        });
        $this->entrar('administrador');

        foreach (range(1, 5) as $i) {
            $this->post('/verificacion', ['codigo' => '000000']);
        }

        $this->post('/verificacion', ['codigo' => $codigo])->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_el_codigo_vence(): void
    {
        config(['seguridad.doble_verificacion' => true]);
        $codigo = null;
        Mail::shouldReceive('raw')->andReturnUsing(function (string $texto) use (&$codigo) {
            preg_match('/(\d{6})/', $texto, $m);
            $codigo = $m[1];
        });
        $this->entrar('administrador');

        $this->travel(11)->minutes();

        $this->post('/verificacion', ['codigo' => $codigo])->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_si_el_correo_falla_no_entra_ni_se_salta_el_paso(): void
    {
        config(['seguridad.doble_verificacion' => true, 'mail.default' => 'no-existe']);

        [, $respuesta] = $this->entrar('administrador');

        $respuesta->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_las_demas_areas_no_la_usan(): void
    {
        config(['seguridad.doble_verificacion' => true]);

        [, $respuesta] = $this->entrar('reservas');

        $respuesta->assertRedirect('/home');
        $this->assertAuthenticated();
    }

    public function test_copias_solo_para_administracion_y_sin_salir_de_su_carpeta(): void
    {
        $carpeta = Respaldos::carpeta();
        $previos = File::isDirectory($carpeta) ? File::files($carpeta) : [];
        $admin = $this->usuario('administrador');

        foreach (['operaciones', 'reservas', 'contabilidad'] as $rol) {
            $this->actingAs($this->usuario($rol))->get('/copias-de-seguridad')->assertForbidden();
            $this->post('/copias-de-seguridad')->assertForbidden();
        }

        $this->actingAs($admin)->post('/copias-de-seguridad')->assertRedirect()->assertSessionHas('success');
        $nuevo = Respaldos::listar()->first()['nombre'];
        $contenido = gzdecode(File::get(Respaldos::ruta($nuevo)));
        $this->assertStringContainsString('CREATE TABLE `programas`', $contenido);
        $this->assertStringContainsString('INSERT INTO `roles`', $contenido);

        $this->get('/copias-de-seguridad')->assertOk()->assertSee('MB');
        $this->get("/copias-de-seguridad/{$nuevo}")->assertOk();
        $this->get('/copias-de-seguridad/..%2F..%2F.env')->assertNotFound();
        $this->assertNull(Respaldos::ruta('../../.env'));
        $this->assertNull(Respaldos::ruta('base-2026.sql.gz.php'));

        File::delete(Respaldos::ruta($nuevo));
        $this->assertCount(count($previos), File::isDirectory($carpeta) ? File::files($carpeta) : []);
    }
}

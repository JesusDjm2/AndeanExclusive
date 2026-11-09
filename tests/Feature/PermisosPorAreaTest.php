<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

/** Todas las áreas consultan todo; cada una modifica solo lo suyo. */
class PermisosPorAreaTest extends TestCase
{
    public static function accionesProhibidas(): array
    {
        return [
            'Reservas registra un cobro' => ['reservas', 'post', '/programas/{p}/pagos'],
            'Reservas paga a un proveedor' => ['reservas', 'post', '/servicios/{s}/pagos'],
            'Reservas edita el programa' => ['reservas', 'put', '/programas/{p}'],
            'Reservas elimina un programa' => ['reservas', 'delete', '/programas/{p}'],
            'Reservas crea un agente' => ['reservas', 'post', '/agentes'],
            'Contabilidad confirma un servicio' => ['contabilidad', 'patch', '/servicios/{s}/estado'],
            'Contabilidad edita el programa' => ['contabilidad', 'put', '/programas/{p}'],
            'Contabilidad crea un proveedor' => ['contabilidad', 'post', '/proveedors'],
            'Contabilidad entra a Usuarios' => ['contabilidad', 'get', '/users'],
            'Contabilidad sube una imagen' => ['contabilidad', 'post', '/imagenes'],
            'Operaciones registra un cobro' => ['operaciones', 'post', '/programas/{p}/pagos'],
            'Operaciones paga a un proveedor' => ['operaciones', 'post', '/servicios/{s}/pagos'],
            'Operaciones confirma un servicio' => ['operaciones', 'patch', '/servicios/{s}/estado'],
            'Operaciones elimina un programa' => ['operaciones', 'delete', '/programas/{p}'],
            'Operaciones crea un tour' => ['operaciones', 'get', '/tours-espanol/create'],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('accionesProhibidas')]
    public function test_cada_area_no_puede_hacer_lo_de_otra(string $rol, string $metodo, string $ruta): void
    {
        $programa = $this->programa(['estado' => 'confirmado']);
        $servicio = $programa->servicios()->create(['fecha' => '2030-05-11', 'tipo' => 'guia', 'titulo' => 'Guía', 'proveedor_id' => $this->proveedor()->id, 'estado' => 'pendiente', 'costo' => 100, 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $url = str_replace(['{p}', '{s}'], [$programa->id, $servicio->id], $ruta);

        $this->actingAs($this->usuario($rol))->{$metodo}($url, ['nombre' => 'x', 'monto' => 1, 'estado' => 'confirmado'])->assertForbidden();
    }

    public function test_el_margen_solo_lo_ven_contabilidad_y_administracion(): void
    {
        $programa = $this->programa(['estado' => 'confirmado']);
        $url = "/programas/{$programa->id}/edit?tab=precios";

        $this->actingAs($this->usuario('reservas'))->get($url)->assertOk()->assertDontSee('Margen actual')->assertDontSee('Margen Ganancia (%)');
        $this->actingAs($this->usuario('operaciones'))->get($url)->assertOk()->assertDontSee('Margen actual')->assertSee('Margen Ganancia (%)');
        $this->actingAs($this->usuario('contabilidad'))->get($url)->assertOk()->assertSee('Margen actual');
        $this->actingAs($this->usuario('administrador'))->get($url)->assertOk()->assertSee('Margen actual');
    }

    public function test_la_ficha_operativa_muestra_el_dia_a_dia_y_el_margen_solo_a_quien_corresponde(): void
    {
        $programa = $this->programa(['estado' => 'confirmado']);
        $programa->servicios()->create(['fecha' => '2030-05-11', 'tipo' => 'guia', 'titulo' => 'Guía en Cusco', 'estado' => 'sin_asignar', 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $url = "/programas/{$programa->id}/ficha";

        $this->actingAs($this->usuario('reservas'))->get($url)->assertOk()->assertSee('Día 2:')->assertSee('Guía en Cusco')->assertDontSee('MARGEN DE GANANCIA');
        $this->actingAs($this->usuario('operaciones'))->get($url)->assertOk()->assertSee('COSTO NETO OPERATIVO')->assertDontSee('MARGEN DE GANANCIA');
        $this->actingAs($this->usuario('contabilidad'))->get($url)->assertOk()->assertSee('MARGEN DE GANANCIA');
    }

    public function test_sin_sesion_se_pide_iniciar_sesion_y_sin_area_no_se_entra(): void
    {
        $this->get('/home')->assertRedirect('/login');

        $this->actingAs(User::factory()->create())->get('/home')->assertForbidden();
    }

    public function test_administracion_crea_y_edita_usuarios_de_cada_area(): void
    {
        $admin = $this->usuario('administrador');
        $this->actingAs($admin);
        $this->get('/users/create')->assertOk()->assertSee('name="role" value="contabilidad"', false)->assertSee('Correo Electrónico');

        foreach (['operaciones', 'reservas', 'contabilidad', 'administrador'] as $rol) {
            $correo = "{$rol}.nuevo@example.com";
            $this->post('/users', ['name' => "Nuevo {$rol}", 'email' => $correo, 'password' => 'Clave-Prueba-123', 'password_confirmation' => 'Clave-Prueba-123', 'role' => $rol])->assertSessionHasNoErrors();
            $this->assertTrue(User::where('email', $correo)->first()->hasRole($rol));
        }

        $carla = User::where('email', 'contabilidad.nuevo@example.com')->first();
        $this->get("/users/{$carla->id}/edit")->assertOk()->assertSee('name="role" value="contabilidad" class="visually-hidden" checked', false);
        $this->put("/users/{$carla->id}", ['name' => 'Otro nombre', 'email' => $carla->email, 'role' => 'contabilidad'])->assertSessionHasNoErrors();
        $this->assertSame('Otro nombre', $carla->fresh()->name);
    }

    public function test_los_errores_del_panel_salen_en_espanol(): void
    {
        $this->actingAs($this->usuario('reservas'))->post('/proveedors', [])->assertSessionHasErrors(['nombre' => 'Completa el nombre.']);
    }

    public function test_un_proveedor_se_crea_sin_ruc_y_valida_al_editar(): void
    {
        $this->actingAs($this->usuario('reservas'));
        $categoria = \App\Models\Categoria::create(['nombre' => 'Guías']);

        $this->post('/proveedors', ['nombre' => 'Sin RUC', 'categoria_id' => $categoria->id, 'telefono' => '+51 900', 'correo' => 'x@example.com', 'ruc' => ''])->assertSessionHasNoErrors()->assertRedirect();
        $proveedor = \App\Models\Proveedor::where('nombre', 'Sin RUC')->firstOrFail();
        $this->assertNull($proveedor->ruc);

        $this->put("/proveedors/{$proveedor->id}", ['nombre' => '', 'categoria_id' => $categoria->id, 'telefono' => '+51 900', 'correo' => 'x@example.com'])->assertSessionHasErrors('nombre');
    }

    public function test_un_itinerario_solo_acepta_idiomas_que_existen(): void
    {
        $programa = $this->programa();

        $this->actingAs($this->usuario('operaciones'))->put("/programas/{$programa->id}", $this->formulario($programa, ['lang' => 'fr']))->assertSessionHasErrors('lang');
    }
}

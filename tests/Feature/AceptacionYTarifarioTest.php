<?php

namespace Tests\Feature;

use App\Models\Aceptacion;
use App\Models\Aviso;
use App\Models\Programa;
use App\Models\Servicio;
use App\Models\Tarifa;
use Tests\TestCase;

class AceptacionYTarifarioTest extends TestCase
{
    private function cotizacion(array $datos = []): Programa
    {
        $programa = $this->programa($datos);
        $programa->paxs()->create(['nombre' => 'Elena', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES']);

        return $programa->fresh();
    }

    public function test_el_cliente_acepta_y_queda_constancia_y_aviso(): void
    {
        $programa = $this->cotizacion();
        $this->comoVisitaWeb();

        $this->get("/itinerario/{$programa->token}")->assertOk()->assertSee('Aceptar propuesta');
        $this->post("/itinerario/{$programa->token}/aceptar", ['nombre' => 'Elena Rivas'])->assertSessionHasErrors('terminos');
        $this->post("/itinerario/{$programa->token}/aceptar", ['terminos' => '1'])->assertSessionHasErrors('nombre');
        $this->assertSame(0, Aceptacion::count());

        $this->post("/itinerario/{$programa->token}/aceptar", ['nombre' => 'Elena Rivas', 'terminos' => '1'])->assertRedirectContains('#propuesta');

        $aceptacion = Aceptacion::sole();
        $this->assertSame('Elena Rivas', $aceptacion->nombre);
        $this->assertEqualsWithDelta(1200, (float) $aceptacion->total, 0.01);
        $this->assertSame('Elena', Programa::find($programa->id)->paxs->first()->nombre);
        $this->assertSame('cotizacion', $programa->fresh()->estado, 'Confirmar sigue siendo decisión del equipo');
        $this->assertTrue(Aviso::where('area', 'operaciones')->where('tipo', 'aceptacion')->exists());
        $this->get("/itinerario/{$programa->token}")->assertSee('Aceptada por Elena Rivas')->assertDontSee('Aceptar propuesta');

        // Repetir no duplica la constancia
        $this->post("/itinerario/{$programa->token}/aceptar", ['nombre' => 'Elena Rivas', 'terminos' => '1']);
        $this->assertSame(1, Aceptacion::count());
    }

    public function test_si_la_propuesta_cambia_hay_que_volver_a_aceptarla(): void
    {
        $programa = $this->cotizacion();
        $this->post("/itinerario/{$programa->token}/aceptar", ['nombre' => 'Elena', 'terminos' => '1']);
        $this->assertNotNull($programa->fresh()->aceptacionVigente());

        $programa->update(['precioAdulto' => 2000]);

        $this->assertNull($programa->fresh()->aceptacionVigente());
        $this->get("/itinerario/{$programa->token}")->assertSee('Aceptar propuesta');
        $this->actingAs($this->usuario('operaciones'))->get("/programas/{$programa->id}/edit")->assertSee('La propuesta cambió después');
    }

    public function test_confirmar_resuelve_el_aviso_y_oculta_el_boton(): void
    {
        $programa = $this->cotizacion();
        $this->comoVisitaWeb()->post("/itinerario/{$programa->token}/aceptar", ['nombre' => 'Elena', 'terminos' => '1']);

        $this->actingAs($this->usuario('operaciones'))->put("/programas/{$programa->id}", $this->formulario($programa->fresh(), ['estado' => 'confirmado']))->assertSessionHasNoErrors();

        $this->assertNotNull(Aviso::where('clave', "aceptacion:{$programa->id}")->first()->resuelto_at);
        auth()->logout();
        $this->get("/itinerario/{$programa->token}")->assertDontSee('Aceptar propuesta');
        $this->post("/itinerario/{$programa->token}/aceptar", ['nombre' => 'Elena', 'terminos' => '1'])->assertNotFound();
    }

    public function test_no_se_puede_aceptar_sin_precio_ni_con_enlace_falso(): void
    {
        $sinPrecio = $this->programa(['precioAdulto' => 0, 'precioChild' => 0]);

        $this->post("/itinerario/{$sinPrecio->token}/aceptar", ['nombre' => 'E', 'terminos' => '1'])->assertNotFound();
        $this->post('/itinerario/token-falso/aceptar', ['nombre' => 'E', 'terminos' => '1'])->assertNotFound();
    }

    public function test_registra_cuando_el_cliente_abre_su_enlace_y_no_el_equipo(): void
    {
        $programa = $this->cotizacion();

        $this->actingAs($this->usuario('operaciones'))->get("/itinerario/{$programa->token}")->assertOk();
        $this->assertNull($programa->fresh()->itinerario_visto_at);

        auth()->logout();
        $this->get("/itinerario/{$programa->token}")->assertOk();
        $this->assertNotNull($programa->fresh()->itinerario_visto_at);
    }

    public function test_reservas_gestiona_el_tarifario_y_las_demas_areas_lo_consultan(): void
    {
        $habitacion = $this->habitacion();
        $proveedor = $this->proveedor();

        $this->actingAs($this->usuario('reservas'));
        $this->get('/tarifario/create')->assertOk();
        $this->post('/tarifario', ['tipo' => 'habitacion', 'desde' => '2030-05-20', 'hasta' => '2030-05-01', 'precio' => 100, 'moneda' => 'USD', 'por' => 'noche'])->assertSessionHasErrors(['habitacion_id', 'hasta']);
        $this->post('/tarifario', ['tipo' => 'habitacion', 'habitacion_id' => $habitacion->id, 'desde' => '2030-05-01', 'hasta' => '2030-09-30', 'precio' => 180, 'moneda' => 'USD', 'por' => 'grupo'])->assertSessionHasNoErrors();
        $this->post('/tarifario', ['tipo' => 'proveedor', 'proveedor_id' => $proveedor->id, 'concepto' => 'Traslado', 'tipo_servicio' => 'nave-espacial', 'precio' => 40, 'moneda' => 'USD', 'por' => 'grupo'])->assertSessionHasErrors('tipo_servicio');
        $this->post('/tarifario', ['tipo' => 'proveedor', 'proveedor_id' => $proveedor->id, 'concepto' => 'Traslado', 'tipo_servicio' => 'traslado', 'desde' => '2030-01-01', 'hasta' => '2030-12-31', 'precio' => 40, 'moneda' => 'USD', 'por' => 'grupo'])->assertSessionHasNoErrors();
        // El tipo llega al itinerario para que elija sola la tarifa del servicio.
        $this->getJson("/tarifario/sugerir?proveedor_id={$proveedor->id}&fecha=2030-06-10")->assertJsonPath('tarifas.0.tipo_servicio', 'traslado');
        $this->get("/proveedors/{$proveedor->id}")->assertOk()->assertSee('name="tipo_servicio"', false);

        $hotel = Tarifa::whereNotNull('habitacion_id')->sole();
        $this->assertSame('noche', $hotel->por, 'Una habitación siempre es por noche');
        $this->get('/tarifario?vista=todas')->assertOk()->assertSee('Hotel de prueba')->assertSee('Traslado');
        $this->put("/tarifario/{$hotel->id}", ['tipo' => 'habitacion', 'habitacion_id' => $habitacion->id, 'desde' => '2030-05-01', 'hasta' => '2030-09-30', 'precio' => 200, 'moneda' => 'USD', 'por' => 'noche'])->assertSessionHasNoErrors();
        $this->assertEqualsWithDelta(200, (float) $hotel->fresh()->precio, 0.01);

        $this->actingAs($this->usuario('contabilidad'));
        $this->get('/tarifario?vista=todas')->assertOk()->assertSee('Hotel de prueba')->assertDontSee('Nueva tarifa');
        $this->post('/tarifario', [])->assertForbidden();
        $this->get('/tarifario/create')->assertForbidden();
        $this->delete("/tarifario/{$hotel->id}")->assertForbidden();
    }

    public function test_sugiere_la_tarifa_de_la_temporada(): void
    {
        $habitacion = $this->habitacion();
        $proveedor = $this->proveedor();
        Tarifa::create(['habitacion_id' => $habitacion->id, 'desde' => '2030-01-01', 'hasta' => '2030-04-30', 'precio' => 100, 'moneda' => 'USD', 'por' => 'noche']);
        Tarifa::create(['habitacion_id' => $habitacion->id, 'desde' => '2030-05-01', 'hasta' => '2030-09-30', 'precio' => 180, 'moneda' => 'USD', 'por' => 'noche']);
        Tarifa::create(['proveedor_id' => $proveedor->id, 'concepto' => 'Traslado', 'desde' => '2030-01-01', 'hasta' => '2030-12-31', 'precio' => 40, 'moneda' => 'PEN', 'por' => 'grupo']);
        $this->actingAs($this->usuario('operaciones'));

        $this->getJson("/tarifario/sugerir?habitacion_id={$habitacion->id}&fecha=2030-06-10")->assertOk()->assertJsonPath('tarifas.0.precio', 180)->assertJsonCount(1, 'tarifas')->assertJsonPath('tarifas.0.fuera_de_temporada', false);
        $this->getJson("/tarifario/sugerir?habitacion_id={$habitacion->id}&fecha=2030-02-10")->assertJsonPath('tarifas.0.precio', 100);
        // Sin tarifa para esa fecha ofrece las de otras temporadas, marcadas como tales.
        $this->getJson("/tarifario/sugerir?habitacion_id={$habitacion->id}&fecha=2031-02-10")->assertJsonCount(2, 'tarifas')
            ->assertJsonPath('tarifas.0.fuera_de_temporada', true)->assertJsonPath('tarifas.1.fuera_de_temporada', true);
        $this->getJson("/tarifario/sugerir?proveedor_id={$proveedor->id}&fecha=2030-06-10")->assertJsonPath('tarifas.0.concepto', 'Traslado')->assertJsonPath('tarifas.0.simbolo', 'S/');
        $this->getJson('/tarifario/sugerir?fecha=2030-06-10')->assertJsonCount(0, 'tarifas');
    }

    public function test_el_servicio_nuevo_toma_la_moneda_del_programa_y_el_guardado_conserva_la_suya(): void
    {
        $programa = $this->programa(['moneda' => 'PEN']);
        $programa->servicios()->create(['fecha' => '2030-05-11', 'tipo' => 'guia', 'titulo' => 'Guía en dólares', 'estado' => 'pendiente', 'moneda' => 'USD', 'costo_por' => 'grupo', 'costo_estimado' => 50]);

        $html = $this->actingAs($this->usuario('operaciones'))->get("/programas/{$programa->id}/edit?tab=itinerario")->assertOk()->getContent();

        // La plantilla de servicio nuevo sigue al programa (soles) sin preguntar; el servicio guardado conserva sus dólares.
        $plantilla = (string) str($html)->after('data-svc-template')->before('</template>');
        $this->assertMatchesRegularExpression('/value="PEN"\s+data-svc-moneda data-moneda-auto="1"/', $plantilla);
        $this->assertMatchesRegularExpression('/data-svc-moneda-toggle[^>]*>S\/<\/button>/', $plantilla);
        $this->assertMatchesRegularExpression('/value="USD"\s+data-svc-moneda data-moneda-auto="0"/', $html);
        // El servicio se elige del tarifario; uno guardado sin proveedor se muestra escrito a mano.
        $this->assertStringContainsString('data-svc-servicio', $plantilla);
        $this->assertMatchesRegularExpression('/data-svc-titulo-libre\s*>\s*<input[^>]+value="Guía en dólares"/', $html);
        // Un solo campo de tipo de cambio (el de Datos principales).
        $this->assertSame(1, substr_count($html, 'name="tipo_cambio"'));
        $this->assertStringNotContainsString('data-tipo-cambio-sync', $html);
    }

    public function test_cada_rubro_de_proveedor_se_ofrece_solo_para_sus_tipos_de_servicio(): void
    {
        $this->assertSame(['traslado', 'tour'], Servicio::tiposDeRubro('Transporte'));
        $this->assertSame(['guia'], Servicio::tiposDeRubro('Guía'));
        $this->assertSame(['tren'], Servicio::tiposDeRubro('Tren y buses'));
        $this->assertSame(['comida'], Servicio::tiposDeRubro('Restaurante'));
        $this->assertSame([], Servicio::tiposDeRubro('Seguros'), 'Un rubro no reconocido sirve para todos los tipos');

        $programa = $this->programa();
        $this->proveedor('Transportes Cusco');
        $html = $this->actingAs($this->usuario('operaciones'))->get("/programas/{$programa->id}/edit?tab=itinerario")->assertOk()->getContent();
        $this->assertMatchesRegularExpression('/<optgroup label="[^"]+" data-tipos="[a-z ]*">/', $html);
    }
}

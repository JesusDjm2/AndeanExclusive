<?php

namespace Tests\Feature;

use App\Models\Agente;
use App\Models\Aviso;
use App\Models\Cliente;
use App\Models\Consulta;
use App\Models\Programa;
use App\Models\Servicio;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ReportesYOperacionDiariaTest extends TestCase
{
    /** Un viaje de 1 200 de venta (adulto 1 000 + 20 %), con 300 de costo y 600 cobrados. */
    private function vendido(array $datos = []): Programa
    {
        $cliente = Cliente::create(['tipo' => 'directo', 'nombre' => 'Cliente', 'origen' => 'whatsapp']);
        $programa = $this->programa($datos + ['estado' => 'confirmado', 'cliente_id' => $cliente->id, 'inicio' => '2030-05-10', 'fin' => '2030-05-12']);
        $programa->paxs()->create(['nombre' => 'Elena', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES', 'pasaporte' => 'XE123456']);
        $programa->servicios()->create(['fecha' => '2030-05-11', 'tipo' => 'guia', 'titulo' => 'Guía', 'proveedor_id' => $this->proveedor()->id, 'estado' => 'confirmado', 'confirmacion' => 'A', 'costo' => 300, 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $programa->pagos()->create(['fecha' => '2030-01-01', 'monto' => 600, 'tipo' => 'deposito']);

        return $programa;
    }

    private const PERIODO = '?desde=2030-01-01&hasta=2030-12-31';

    public function test_el_reporte_suma_ventas_cobros_y_margen(): void
    {
        $programa = $this->vendido();
        $agente = Agente::create(['nombre' => 'Ana Agente', 'telefono' => '1', 'email' => 'a@example.com']);
        $programa->agentes()->sync([$agente->id]);
        $this->programa(['estado' => 'cotizacion', 'inicio' => '2030-05-10', 'fin' => '2030-05-12']);
        $this->travelTo('2030-02-01');
        Consulta::create(['nombre' => 'A', 'estado' => 'perdida', 'motivo_perdida' => 'precio']);
        Consulta::create(['nombre' => 'B', 'estado' => 'ganada']);

        $this->actingAs($this->usuario('contabilidad'))->get('/reportes' . self::PERIODO)->assertOk()
            ->assertSee('$1,200.00')->assertSee('$600.00')->assertSee('$900.00')->assertSee('75.0')
            ->assertSee('Mayo 2030')->assertSee('Ana Agente')->assertSee('WhatsApp')->assertSee('Transportes Prueba')->assertSee('50.0 % ganadas')->assertSee('Precio');
    }

    public function test_las_cotizaciones_y_los_viajes_de_otro_periodo_no_cuentan(): void
    {
        $this->vendido();

        $this->actingAs($this->usuario('contabilidad'))->get('/reportes?desde=2031-01-01&hasta=2031-12-31')->assertOk()->assertDontSee('$1,200.00')->assertSee('Sin programas confirmados');
    }

    public function test_el_margen_y_los_proveedores_solo_para_contabilidad_y_administracion(): void
    {
        $this->vendido();

        foreach (['operaciones', 'reservas'] as $rol) {
            $this->actingAs($this->usuario($rol));
            $this->get('/reportes' . self::PERIODO)->assertOk()->assertSee('$1,200.00')->assertDontSee('Margen')->assertDontSee('Por proveedor');
            $this->get('/reportes/exportar/proveedor' . self::PERIODO)->assertForbidden();
            $this->assertStringNotContainsString('Margen', $this->get('/reportes/exportar/mes' . self::PERIODO)->streamedContent());
        }

        $this->actingAs($this->usuario('administrador'));
        $this->get('/reportes' . self::PERIODO)->assertOk()->assertSee('Margen')->assertSee('Por proveedor');
        $csv = $this->get('/reportes/exportar/mes' . self::PERIODO)->assertOk()->streamedContent();
        $this->assertStringStartsWith("\xEF\xBB\xBF", $csv);
        $this->assertStringContainsString('Margen (USD)', $csv);
        $this->assertStringContainsString('1200,00', $csv);
        $this->get('/reportes/exportar/otra' . self::PERIODO)->assertNotFound();
    }

    public function test_el_excel_no_ejecuta_formulas_de_los_nombres(): void
    {
        $programa = $this->vendido();
        $agente = Agente::create(['nombre' => '=HYPERLINK("http://mal")', 'telefono' => '1', 'email' => 'a@example.com']);
        $programa->agentes()->sync([$agente->id]);

        $csv = $this->actingAs($this->usuario('contabilidad'))->get('/reportes/exportar/agente' . self::PERIODO)->streamedContent();

        $this->assertStringNotContainsString("\n=HYPERLINK", $csv);
        $this->assertStringContainsString("'=HYPERLINK", $csv);
    }

    public function test_lista_de_huespedes_por_hotel_y_sin_pasaporte_para_contabilidad(): void
    {
        $programa = $this->vendido();
        $doble = $this->habitacion();
        $programa->habitacionesFechas()->create(['habitacion_id' => $doble->id, 'cantidad' => 2, 'fecha_ingreso' => '2030-05-10', 'fecha_salida' => '2030-05-12']);
        $url = "/programas/{$programa->id}/lista-de-huespedes";

        $this->actingAs($this->usuario('reservas'))->get($url)->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->get("{$url}?hotel={$doble->hotel_id}")->assertOk();
        $this->get("{$url}?hotel=999999")->assertNotFound();
        $this->get("/programas/{$programa->id}/edit?tab=alojamiento")->assertOk()->assertSee('Lista de huéspedes para el hotel');

        $html = view('programas.rooming', ['programa' => $programa->load('paxs'), 'hoteles' => collect([$doble->hotel_id => $doble->hotel]), 'estadias' => $programa->habitacionesFechas()->get()->groupBy(fn ($e) => $e->habitacion->hotel_id), 'pasaportes' => false])->render();
        $this->assertStringNotContainsString('XE123456', $html);
        $this->assertStringContainsString('2 × Doble', $html);
    }

    public function test_enviar_la_orden_por_correo(): void
    {
        $programa = $this->vendido();
        $servicio = $programa->servicios()->first();
        $this->actingAs($this->usuario('reservas'));

        $this->get('/reservas?bandeja=confirmados&periodo=todos')->assertOk()->assertSee('Enviar orden de Guía por correo');
        $this->post("/servicios/{$servicio->id}/enviar-orden")->assertSessionHas('success');
        $this->assertNotNull($servicio->fresh()->orden_enviada_at);
        $this->assertTrue($servicio->eventos()->where('detalle', 'like', 'Orden de servicio enviada%')->exists());

        $servicio->proveedor->update(['correo' => 'no-es-correo']);
        $this->post("/servicios/{$servicio->id}/enviar-orden")->assertSessionHas('error');

        $this->actingAs($this->usuario('contabilidad'))->post("/servicios/{$servicio->id}/enviar-orden")->assertForbidden();
    }

    public function test_los_vuelos_salen_en_la_hoja_del_dia(): void
    {
        $programa = $this->vendido();
        $this->actingAs($this->usuario('operaciones'));

        $this->put("/programas/{$programa->id}", $this->formulario($programa, ['llegada_vuelo' => 'LA 2021', 'llegada_at' => '2030-05-10T10:30', 'salida_vuelo' => 'LA 2022', 'salida_at' => '2030-05-12T18:00']))->assertSessionHasNoErrors();

        $this->get('/operaciones?fecha=2030-05-10')->assertOk()->assertSee('Vuelos del día')->assertSee('10:30 · LA 2021')->assertSee('Llegada')->assertDontSee('LA 2022');
        $this->get('/operaciones?fecha=2030-05-11')->assertOk()->assertDontSee('Vuelos del día');
    }

    public function test_cerrar_exige_todo_listo_y_bloquea_los_cambios(): void
    {
        $this->travelTo('2030-06-01');
        $programa = $this->vendido();
        $servicio = $programa->servicios()->first();
        $contabilidad = $this->usuario('contabilidad');
        $this->actingAs($contabilidad);

        $this->post("/programas/{$programa->id}/cerrar")->assertSessionHas('error', fn ($t) => str_contains($t, 'saldo por pagar') && str_contains($t, 'sin pagar al proveedor'));
        $this->assertNull($programa->fresh()->cerrado_at);

        $programa->pagos()->create(['fecha' => '2030-01-02', 'monto' => 600, 'tipo' => 'saldo']);
        $servicio->pagos()->create(['fecha' => '2030-01-02', 'monto' => 300, 'moneda' => 'USD']);
        $this->artisan('avisos:plazos');
        $this->assertTrue(Aviso::where('clave', "plazo:cerrar:{$programa->id}")->exists());
        $this->get("/programas/{$programa->id}/edit")->assertSee('Cerrar programa');

        $this->post("/programas/{$programa->id}/cerrar")->assertSessionHas('success');
        $this->assertNotNull($programa->fresh()->cerrado_at);

        // Cerrado: ninguna área lo cambia, ni por el programa ni por sus servicios
        $this->from("/programas/{$programa->id}/edit")->post("/programas/{$programa->id}/pagos", ['fecha' => '2030-01-03', 'monto' => 1, 'tipo' => 'otro'])->assertSessionHas('error');
        $this->assertSame(2, $programa->pagos()->count());
        $this->actingAs($this->usuario('operaciones'))->put("/programas/{$programa->id}", $this->formulario($programa->fresh(), ['nombre' => 'Cambiado']))->assertSessionHas('error');
        $this->assertNotSame('Cambiado', $programa->fresh()->nombre);
        $this->actingAs($this->usuario('reservas'))->patch("/servicios/{$servicio->id}/estado", ['estado' => 'cancelado'])->assertSessionHas('error');
        $this->assertSame('confirmado', Servicio::find($servicio->id)->estado);

        // Reabrir: solo Administración
        $this->actingAs($contabilidad)->post("/programas/{$programa->id}/reabrir")->assertForbidden();
        $this->actingAs($this->usuario('administrador'))->post("/programas/{$programa->id}/reabrir")->assertSessionHas('success');
        $this->assertNull($programa->fresh()->cerrado_at);
    }

    public function test_solo_contabilidad_y_administracion_cierran(): void
    {
        $programa = $this->vendido();

        $this->actingAs($this->usuario('operaciones'))->post("/programas/{$programa->id}/cerrar")->assertForbidden();
        $this->actingAs($this->usuario('reservas'))->post("/programas/{$programa->id}/cerrar")->assertForbidden();
    }
}

<?php

namespace Tests\Feature;

use App\Models\Cliente;
use App\Models\Programa;
use App\Models\Servicio;
use Tests\TestCase;

class CopiaDeProgramaTest extends TestCase
{
    private function programaArmado(): Programa
    {
        $programa = $this->programa(['estado' => 'confirmado', 'nombre' => 'Cusco clásico', 'inicio' => '2030-05-10', 'fin' => '2030-05-12', 'presentacion' => '<p>Hola</p>']);
        $programa->paxs()->create(['nombre' => 'Elena', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES']);
        $programa->dias()->create(['fecha' => '2030-05-11', 'titulo' => 'Día dos']);
        $proveedor = $this->proveedor();
        $s = $programa->servicios()->create(['fecha' => '2030-05-11', 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Guía', 'proveedor_id' => $proveedor->id, 'estado' => 'confirmado', 'confirmacion' => 'A1', 'costo' => 95, 'costo_estimado' => 80, 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $s->pagos()->create(['fecha' => now()->toDateString(), 'monto' => 10, 'moneda' => 'USD']);
        $programa->servicios()->create(['fecha' => '2030-05-11', 'tipo' => 'otro', 'titulo' => 'Cancelado', 'estado' => 'cancelado', 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $programa->habitacionesFechas()->create(['habitacion_id' => $this->habitacion()->id, 'cantidad' => 2, 'costo_noche' => 100, 'moneda' => 'USD', 'fecha_ingreso' => '2030-05-10', 'fecha_salida' => '2030-05-12']);
        $programa->pagos()->create(['fecha' => now()->toDateString(), 'monto' => 100, 'tipo' => 'deposito']);

        return $programa;
    }

    public function test_duplicar_corre_las_fechas_y_no_copia_lo_del_cliente(): void
    {
        $origen = $this->programaArmado();
        $otro = Cliente::create(['tipo' => 'directo', 'nombre' => 'Otro cliente']);
        $this->actingAs($this->usuario('operaciones'));

        $this->get("/programas/{$origen->id}/duplicar")->assertOk();
        $this->post("/programas/{$origen->id}/duplicar", ['nombre' => 'Cusco para Otro', 'cliente_id' => $otro->id, 'inicio' => '2031-01-10'])->assertSessionHasNoErrors()->assertRedirect();

        $copia = Programa::where('nombre', 'Cusco para Otro')->sole();
        $this->assertSame('cotizacion', $copia->estado);
        $this->assertNotSame($origen->codigo, $copia->codigo);
        $this->assertNotSame($origen->token, $copia->token);
        $this->assertSame('2031-01-10', (string) $copia->inicio);
        $this->assertSame('2031-01-12', (string) $copia->fin);
        $this->assertSame($otro->id, $copia->cliente_id);
        $this->assertSame('2031-01-11', $copia->dias()->first()->fecha->toDateString());
        $this->assertSame(0, $copia->paxs()->count());
        $this->assertSame(0, $copia->pagos()->count());

        $guia = $copia->servicios()->where('titulo', 'Guía')->sole();
        $this->assertSame('2031-01-11', $guia->fecha->toDateString());
        $this->assertSame('pendiente', $guia->estado);
        $this->assertNull($guia->confirmacion);
        $this->assertNull($guia->costo);
        $this->assertEqualsWithDelta(80, $guia->costo_estimado, 0.01);
        $this->assertSame(0, $guia->pagos()->count());
        $this->assertFalse($copia->servicios()->where('titulo', 'Cancelado')->exists());

        $estadia = $copia->habitacionesFechas()->sole();
        $this->assertSame('2031-01-10', (string) $estadia->fecha_ingreso);
        $this->assertNotNull($estadia->servicio_id, 'El hotel genera su propio servicio');
        $this->assertSame(1, $copia->servicios()->where('tipo', 'alojamiento')->count());
        $this->assertEqualsWithDelta(400, $estadia->servicio->costo_estimado, 0.01);
        $this->assertSame(1, $origen->fresh()->servicios()->where('confirmacion', 'A1')->count(), 'El original no cambia');
    }

    public function test_la_plantilla_no_cuenta_como_viaje_pero_se_puede_usar(): void
    {
        $origen = $this->programaArmado();
        $operaciones = $this->usuario('operaciones');
        $this->actingAs($operaciones)->post("/programas/{$origen->id}/plantilla")->assertRedirect();

        $plantilla = Programa::plantillas()->sole();
        $this->assertStringStartsWith('PLT-', $plantilla->codigo);
        $this->assertNull($plantilla->cliente_id);
        $this->assertNull($plantilla->anio_id);
        $this->assertSame('2000-01-03', (string) $plantilla->inicio);
        $this->assertSame(1, Programa::count(), 'Fuera de las consultas normales');

        $this->get('/programas?estado=todos')->assertOk()->assertDontSee($plantilla->codigo);
        $this->get('/programas/plantillas')->assertOk()->assertSee('Cusco clásico');
        $this->get("/programas/{$plantilla->id}/edit")->assertOk()->assertSee('Esto es una plantilla');
        $this->assertSame(0, Servicio::query()->operables()->whereKey($plantilla->servicios()->pluck('id'))->count());
        $this->get('/reservas?bandeja=por_reservar&periodo=todos&q=' . $plantilla->codigo)->assertOk()->assertDontSee('id="servicio-', false);

        // Se puede editar y sigue siendo plantilla aunque intenten confirmarla
        $this->put("/programas/{$plantilla->id}", $this->formulario($plantilla, ['cliente_id' => '', 'estado' => 'confirmado', 'nombre' => 'Cusco clásico v2']))->assertSessionHasNoErrors();
        $this->assertSame('cotizacion', $plantilla->fresh()->estado);
        $this->assertSame('Cusco clásico v2', $plantilla->fresh()->nombre);

        $cliente = Cliente::create(['tipo' => 'directo', 'nombre' => 'Nuevo']);
        $this->post("/programas/{$plantilla->id}/duplicar", ['nombre' => 'Viaje real', 'cliente_id' => $cliente->id, 'inicio' => '2032-03-01'])->assertSessionHasNoErrors();
        $real = Programa::where('nombre', 'Viaje real')->sole();
        $this->assertFalse($real->plantilla);
        $this->assertSame('2032-03-03', (string) $real->fin);
        $this->assertNotNull($real->anio_id);

        $this->delete("/programas/{$plantilla->id}/plantilla")->assertRedirect('/programas/plantillas');
        $this->assertSame(0, Programa::plantillas()->count());
    }

    public function test_no_se_elimina_un_viaje_real_por_la_ruta_de_plantillas(): void
    {
        $programa = $this->programa();

        $this->actingAs($this->usuario('operaciones'))->delete("/programas/{$programa->id}/plantilla")->assertNotFound();
        $this->assertTrue(Programa::whereKey($programa->id)->exists());
    }

    public function test_solo_operaciones_y_administracion_copian(): void
    {
        $programa = $this->programa();
        foreach (['reservas', 'contabilidad'] as $rol) {
            $this->actingAs($this->usuario($rol));
            $this->get("/programas/{$programa->id}/duplicar")->assertForbidden();
            $this->post("/programas/{$programa->id}/duplicar", [])->assertForbidden();
            $this->post("/programas/{$programa->id}/plantilla")->assertForbidden();
            $this->get('/programas/plantillas')->assertOk()->assertDontSee('fa-plus');
        }
    }

    public function test_la_hora_del_sistema_es_la_de_peru(): void
    {
        $this->assertSame('America/Lima', config('app.timezone'));
    }
}

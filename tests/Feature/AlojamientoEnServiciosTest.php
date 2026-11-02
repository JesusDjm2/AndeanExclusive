<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Programa;
use App\Models\Servicio;
use Tests\TestCase;

class AlojamientoEnServiciosTest extends TestCase
{
    private function estadia(int $habitacion, array $c = []): array
    {
        return $c + ['habitacion_id' => $habitacion, 'cantidad' => 3, 'ingreso' => '2030-05-10', 'salida' => '2030-05-12', 'costo_noche' => 100, 'moneda' => 'USD'];
    }

    private function guardar(Programa $programa, array $estadias, array $extra = [])
    {
        return $this->put("/programas/{$programa->id}", $this->formulario($programa, ['_estadias' => 1, 'estadias' => $estadias] + $extra));
    }

    public function test_cada_estadia_crea_su_servicio_con_costo_y_proveedor(): void
    {
        $programa = $this->programa();
        $doble = $this->habitacion();
        $this->actingAs($this->usuario('operaciones'));

        $this->guardar($programa, [$this->estadia($doble->id)])->assertSessionHasNoErrors();

        $servicio = $programa->servicios()->where('tipo', 'alojamiento')->sole();
        $this->assertEqualsWithDelta(600, $servicio->costo_estimado, 0.01, '3 habitaciones × 2 noches × 100');
        $this->assertEqualsWithDelta(600, $servicio->costo, 0.01);
        $this->assertSame('pendiente', $servicio->estado, 'El hotel ya es su proveedor: queda por reservar');
        $this->assertSame('Hotel de prueba', $servicio->proveedor->nombre);
        $this->assertFalse((bool) $servicio->visible_cliente);
        $this->assertStringContainsString('3 × Doble · 2 noches', $servicio->titulo);
        $this->assertEqualsWithDelta(600, $programa->fresh()->costeo()['costo'], 0.01, 'Suma al margen');
    }

    public function test_el_hotel_reutiliza_su_proveedor(): void
    {
        $programa = $this->programa();
        $doble = $this->habitacion();
        $this->actingAs($this->usuario('operaciones'));

        $this->guardar($programa, [$this->estadia($doble->id), $this->estadia($doble->id, ['ingreso' => '2030-05-12', 'salida' => '2030-05-13'])]);

        $this->assertSame(2, $programa->servicios()->where('tipo', 'alojamiento')->count());
        $this->assertSame(1, $programa->servicios()->where('tipo', 'alojamiento')->distinct()->count('proveedor_id'));
    }

    public function test_sin_fechas_no_genera_servicio_y_al_quitarlas_lo_retira(): void
    {
        $programa = $this->programa();
        $doble = $this->habitacion();
        $this->actingAs($this->usuario('operaciones'));

        $this->guardar($programa, [$this->estadia($doble->id, ['ingreso' => '', 'salida' => ''])])->assertSessionHasNoErrors();
        $this->assertSame(0, $programa->servicios()->count());

        $fila = $programa->habitacionesFechas()->first();
        $this->guardar($programa, [$this->estadia($doble->id, ['id' => $fila->id])]);
        $this->assertSame(1, $programa->servicios()->count());

        $this->guardar($programa, [$this->estadia($doble->id, ['id' => $fila->id, 'ingreso' => '', 'salida' => ''])]);
        $this->assertSame(0, $programa->servicios()->count());
    }

    public function test_cambiar_la_estadia_actualiza_su_servicio_y_avisa_a_reservas(): void
    {
        $programa = $this->programa(['estado' => 'confirmado']);
        $doble = $this->habitacion();
        $this->actingAs($this->usuario('operaciones'));
        $this->guardar($programa, [$this->estadia($doble->id)]);
        $fila = $programa->habitacionesFechas()->first();
        $fila->servicio->update(['estado' => 'confirmado', 'confirmacion' => 'H-1']);

        $this->guardar($programa, [$this->estadia($doble->id, ['id' => $fila->id, 'cantidad' => 2, 'salida' => '2030-05-13'])])->assertSessionHasNoErrors();

        $servicio = $fila->fresh()->servicio;
        $this->assertEqualsWithDelta(600, $servicio->costo_estimado, 0.01, '2 habitaciones × 3 noches × 100');
        $this->assertSame('H-1', $servicio->confirmacion, 'No pierde su confirmación');
        $this->assertTrue(Aviso::where('area', 'reservas')->where('tipo', 'alojamiento')->exists());
    }

    public function test_el_costo_que_puso_reservas_no_se_pisa(): void
    {
        $programa = $this->programa();
        $doble = $this->habitacion();
        $this->actingAs($this->usuario('operaciones'));
        $this->guardar($programa, [$this->estadia($doble->id)]);
        $fila = $programa->habitacionesFechas()->first();
        $fila->servicio->update(['costo' => 750]);

        $this->guardar($programa, [$this->estadia($doble->id, ['id' => $fila->id, 'costo_noche' => 110])]);

        $servicio = $fila->fresh()->servicio;
        $this->assertEqualsWithDelta(660, $servicio->costo_estimado, 0.01);
        $this->assertEqualsWithDelta(750, $servicio->costo, 0.01);
    }

    public function test_el_servicio_no_se_borra_al_guardar_el_itinerario_y_se_va_con_su_estadia(): void
    {
        $programa = $this->programa();
        $doble = $this->habitacion();
        $this->actingAs($this->usuario('operaciones'));
        $this->guardar($programa, [$this->estadia($doble->id)]);
        $fila = $programa->habitacionesFechas()->first();

        // La pantalla envía las dos pestañas a la vez; el servicio recién creado aún no viene en el itinerario.
        $this->guardar($programa, [$this->estadia($doble->id, ['id' => $fila->id])], ['_itinerario' => 1, 'servicios' => []])->assertSessionHasNoErrors();
        $this->assertSame(1, $programa->servicios()->count());

        $this->guardar($programa, [])->assertSessionHasNoErrors();
        $this->assertSame(0, $programa->habitacionesFechas()->count());
        $this->assertSame(0, $programa->servicios()->count());
    }

    public function test_una_estadia_con_pagos_no_se_quita(): void
    {
        $programa = $this->programa();
        $doble = $this->habitacion();
        $this->actingAs($this->usuario('operaciones'));
        $this->guardar($programa, [$this->estadia($doble->id)]);
        $fila = $programa->habitacionesFechas()->first();
        $fila->servicio->pagos()->create(['fecha' => now()->toDateString(), 'monto' => 100, 'moneda' => 'USD']);

        $this->guardar($programa, []);

        $this->assertSame(1, $programa->habitacionesFechas()->count());
        $this->assertTrue(Servicio::whereKey($fila->servicio_id)->exists());
    }

    public function test_los_costos_cargados_a_mano_no_se_duplican(): void
    {
        $programa = $this->programa();
        $doble = $this->habitacion();
        $programa->servicios()->create(['fecha' => '2030-05-10', 'tipo' => 'alojamiento', 'titulo' => 'Hotel a mano', 'estado' => 'sin_asignar', 'costo_estimado' => 500, 'moneda' => 'USD', 'costo_por' => 'grupo', 'visible_cliente' => false]);
        $this->actingAs($this->usuario('operaciones'));

        $this->guardar($programa, [$this->estadia($doble->id)]);

        $this->assertSame(1, $programa->servicios()->count());
    }

    public function test_avisa_si_falta_el_costo_y_si_el_suplemento_no_coincide(): void
    {
        $programa = $this->programa();
        $individual = $this->habitacion('Individual', 1);
        $this->actingAs($this->usuario('operaciones'));

        $this->guardar($programa, [$this->estadia($individual->id, ['cantidad' => 2, 'costo_noche' => ''])])
            ->assertSessionHas('warning', fn ($t) => str_contains($t, 'Falta el costo por noche'));

        $this->assertSame(2, $programa->fresh()->individualesSegunAlojamiento());
        $this->get("/programas/{$programa->id}/edit?tab=precios")->assertOk()->assertSee('Según Alojamiento hay 2 habitaciones individuales');
    }

    public function test_reservas_no_ve_el_costo_por_noche(): void
    {
        $programa = $this->programa();
        $this->actingAs($this->usuario('reservas'))->get("/programas/{$programa->id}/edit?tab=alojamiento")->assertOk()->assertDontSee('data-estadia-costo', false);
    }
}

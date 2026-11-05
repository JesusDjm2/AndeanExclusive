<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Movimiento;
use App\Models\Pax;
use App\Models\Programa;
use App\Models\ProveedorPago;
use App\Models\Servicio;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/** Un viaje de principio a fin, con cada área haciendo su parte. Es el recorrido que explica la guía del equipo. */
class RecorridoDeUnViajeTest extends TestCase
{
    private function aviso(string $area, string $tipo): bool
    {
        return Aviso::where('area', $area)->where('tipo', $tipo)->exists();
    }

    public function test_un_viaje_completo_avanza_por_todas_las_areas(): void
    {
        Mail::fake();
        $ana = $this->usuario('operaciones', 'Ana Torres');
        $rosa = $this->usuario('reservas', 'Rosa Vargas');
        $carla = $this->usuario('contabilidad', 'Carla Medina');
        $proveedor = $this->proveedor();
        $doble = $this->habitacion();

        // Operaciones crea la cotización con cliente nuevo, pasajeros y 3 × Doble
        $this->actingAs($ana)->post('/programas', [
            'nombre' => 'Cusco en familia', 'estado' => 'cotizacion', 'lang' => 'es', 'inicio' => '2030-05-10', 'fin' => '2030-05-16',
            'precioAdulto' => 1250, 'precioChild' => 950, 'porcentaje_ganancia' => 20,
            'cliente_modo' => 'nuevo', 'cliente_nuevo' => ['tipo' => 'directo', 'nombre' => 'Elena Rivas', 'email' => 'elena@example.com'],
            'paxs' => [
                ['nombre' => 'Elena Rivas', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES', 'pasaporte' => 'XE1', 'pasaporte_vencimiento' => '2035-01-10'],
                ['nombre' => 'Pablo Rivas', 'fecha_nacimiento' => '2022-09-09', 'pais' => 'ES'],
            ],
            '_estadias' => 1, 'estadias' => [['habitacion_id' => $doble->id, 'cantidad' => 3, 'ingreso' => '2030-05-10', 'salida' => '2030-05-12']],
        ])->assertRedirect()->assertSessionHasNoErrors();

        $programa = Programa::where('nombre', 'Cusco en familia')->firstOrFail();
        $this->assertStringStartsWith('AET-2030-', $programa->codigo);
        $this->assertNotNull($programa->anio_id);
        $this->assertSame(3, $programa->habitacionesFechas()->first()->cantidad);

        // Arma el itinerario
        $this->put("/programas/{$programa->id}", $this->formulario($programa, ['_itinerario' => 1, 'dias' => ['2030-05-10' => ['titulo' => 'Llegada']], 'servicios' => [
            ['fecha' => '2030-05-10', 'hora' => '10:30', 'tipo' => 'traslado', 'titulo' => 'Traslado', 'estado' => 'sin_asignar', 'costo_estimado' => 40, 'moneda' => 'USD', 'costo_por' => 'grupo', 'visible_cliente' => 1],
            ['fecha' => '2030-05-11', 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Guía', 'estado' => 'sin_asignar', 'costo_estimado' => 80, 'moneda' => 'USD', 'costo_por' => 'grupo', 'visible_cliente' => 1],
        ]]))->assertSessionHasNoErrors();

        $this->assertSame(2, $programa->servicios()->where('estado', 'sin_asignar')->count());
        $this->assertSame(0, Aviso::count(), 'Una cotización no genera avisos');

        // No se puede confirmar sin fechas; con ellas, avisa a las áreas
        $this->put("/programas/{$programa->id}", $this->formulario($programa, ['estado' => 'confirmado', 'inicio' => '', 'fin' => '']))->assertSessionHasErrors(['inicio', 'fin']);
        $this->put("/programas/{$programa->id}", $this->formulario($programa, ['estado' => 'confirmado']))->assertSessionHasNoErrors();
        $programa->refresh();
        $this->assertNotNull($programa->confirmado_at);
        $this->assertTrue($this->aviso('reservas', 'programa_confirmado'));
        $this->assertTrue($this->aviso('contabilidad', 'programa_confirmado'));

        // Contabilidad registra el depósito
        $cobro = $programa->cobranza();
        $this->assertEqualsWithDelta($cobro['total'] / 2, $cobro['deposito'], 0.01);
        $this->actingAs($carla)->post("/programas/{$programa->id}/pagos", ['fecha' => now()->toDateString(), 'monto' => $cobro['deposito'], 'tipo' => 'deposito', 'metodo' => 'transferencia'])->assertSessionHasNoErrors();
        $this->assertTrue($programa->fresh()->cobranza()['depositoCubierto']);
        $this->assertTrue($this->aviso('operaciones', 'cobro'));

        // Reservas asigna, solicita y confirma
        $traslado = $programa->servicios()->where('titulo', 'Traslado')->first();
        $guia = $programa->servicios()->where('titulo', 'Guía')->first();
        $this->actingAs($rosa)->patch("/servicios/{$traslado->id}/proveedor", ['proveedor_id' => $proveedor->id, 'costo' => 40])->assertSessionHasNoErrors();
        $this->assertSame('pendiente', $traslado->fresh()->estado);
        $this->patch("/servicios/{$guia->id}/proveedor", ['proveedor_id' => $proveedor->id, 'costo' => 95])->assertSessionHasNoErrors();
        $this->assertTrue($this->aviso('contabilidad', 'costo'));

        $this->patch("/servicios/{$traslado->id}/estado", ['estado' => 'solicitado']);
        $this->patch("/servicios/{$traslado->id}/estado", ['estado' => 'confirmado'])->assertSessionHas('error');
        $this->assertSame('solicitado', $traslado->fresh()->estado);
        $this->patch("/servicios/{$traslado->id}/estado", ['estado' => 'confirmado', 'confirmacion' => 'SOL-1']);
        $this->patch("/servicios/{$guia->id}/estado", ['estado' => 'confirmado', 'confirmacion' => 'GV-1']);
        $this->assertTrue($this->aviso('operaciones', 'servicio_confirmado'));
        $this->get("/servicios/{$traslado->id}/orden")->assertOk();

        // Pide el pago y Contabilidad lo registra, total o parcial
        $this->post("/servicios/{$guia->id}/solicitar-pago");
        $this->assertTrue($this->aviso('contabilidad', 'pago_solicitado'));
        $this->actingAs($carla)->post("/servicios/{$guia->id}/pagos", ['monto' => 50, 'fecha' => now()->toDateString()])->assertSessionHasNoErrors();
        $this->assertNull($guia->fresh()->pagado_at);
        $this->post('/cuentas-por-pagar/pagar', ['servicios' => [$guia->id, $traslado->id], 'fecha' => now()->toDateString()])->assertSessionHasNoErrors();
        $this->assertNotNull($guia->fresh()->pagado_at);
        $this->assertTrue(Aviso::where('clave', "pago:{$guia->id}")->whereNotNull('resuelto_at')->exists(), 'El aviso se resuelve solo');

        // Anular exige motivo y deja constancia
        $pago = $traslado->fresh()->pagos()->first();
        $this->delete("/servicios/{$traslado->id}/pagos/{$pago->id}", ['motivo' => ''])->assertSessionHasErrors('motivo');
        $this->delete("/servicios/{$traslado->id}/pagos/{$pago->id}", ['motivo' => 'Se registró en otro servicio'])->assertSessionHasNoErrors();
        $this->assertTrue(ProveedorPago::onlyTrashed()->whereKey($pago->id)->exists());
        $this->assertTrue($this->aviso('reservas', 'pago_anulado'));

        // Operaciones envía un correo manual al cliente
        $this->actingAs($ana)->post("/programas/{$programa->id}/notificaciones", ['tipo' => 'saldo'])->assertSessionHasNoErrors();
        $this->assertSame(1, $programa->notificaciones()->count());

        // Cancelar avisa a las áreas y conserva el programa
        $this->put("/programas/{$programa->id}", $this->formulario($programa->fresh(), ['estado' => 'cancelado']))->assertSessionHasNoErrors();
        $this->assertTrue($this->aviso('reservas', 'programa_cancelado'));
        $this->assertTrue($this->aviso('contabilidad', 'programa_cancelado'));
        $this->assertTrue(Movimiento::where('usuario', 'Carla Medina')->exists());
    }

    public function test_el_cliente_completa_sus_datos_y_operaciones_recibe_el_aviso(): void
    {
        $programa = $this->programa(['estado' => 'confirmado']);
        $pax = $programa->paxs()->create(['nombre' => 'Elena', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES']);

        $this->comoVisitaWeb()->put("/itinerario/{$programa->token}/pasajeros", ['paxs' => [
            ['id' => $pax->id, 'nombre' => 'Elena', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES', 'pasaporte' => 'XE9', 'pasaporte_vencimiento' => '2035-01-01'],
        ]])->assertRedirect();

        $this->assertSame('XE9', Pax::find($pax->id)->pasaporte);
        $this->assertTrue($this->aviso('operaciones', 'datos_cliente'));
        $this->assertTrue($programa->tareas()->where('titulo', 'Revisar datos de pasajeros enviados por el cliente')->exists());
    }

    public function test_el_formulario_del_cliente_se_cierra_al_cancelar(): void
    {
        $programa = $this->programa(['estado' => 'cancelado']);

        $this->get("/itinerario/{$programa->token}/pasajeros")->assertNotFound();
    }

    public function test_los_cambios_en_servicios_reservados_avisan_a_reservas(): void
    {
        $ana = $this->usuario('operaciones');
        $programa = $this->programa(['estado' => 'confirmado']);
        $proveedor = $this->proveedor();
        $s = $programa->servicios()->create(['fecha' => '2030-05-11', 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Guía', 'proveedor_id' => $proveedor->id, 'estado' => 'solicitado', 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $fila = fn (array $c = []) => $c + ['id' => $s->id, 'fecha' => '2030-05-11', 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Guía', 'proveedor_id' => $proveedor->id, 'estado' => 'solicitado', 'moneda' => 'USD', 'costo_por' => 'grupo'];

        $this->actingAs($ana)->put("/programas/{$programa->id}", $this->formulario($programa, ['_itinerario' => 1, 'servicios' => [$fila(['hora' => '10:00'])]]))->assertSessionHasNoErrors();
        $this->assertTrue($this->aviso('reservas', 'servicio_fecha'));

        $this->put("/programas/{$programa->id}", $this->formulario($programa, ['_itinerario' => 1, 'servicios' => []]))->assertSessionHasNoErrors();
        $this->assertFalse(Servicio::whereKey($s->id)->exists());
        $this->assertTrue($this->aviso('reservas', 'servicio_quitado'));
    }

    public function test_un_servicio_con_pagos_no_se_quita(): void
    {
        $programa = $this->programa(['estado' => 'confirmado']);
        $s = $programa->servicios()->create(['fecha' => '2030-05-11', 'tipo' => 'guia', 'titulo' => 'Guía', 'proveedor_id' => $this->proveedor()->id, 'estado' => 'confirmado', 'confirmacion' => 'A1', 'costo' => 100, 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $s->pagos()->create(['fecha' => now()->toDateString(), 'monto' => 50, 'moneda' => 'USD']);

        $this->actingAs($this->usuario('operaciones'))->put("/programas/{$programa->id}", $this->formulario($programa, ['_itinerario' => 1, 'servicios' => []]))->assertSessionHas('error');
        $this->assertTrue(Servicio::whereKey($s->id)->exists());
    }

    public function test_avisa_al_guardar_lo_que_conviene_revisar(): void
    {
        $programa = $this->programa(['estado' => 'cotizacion']);
        $programa->servicios()->create(['fecha' => '2030-05-20', 'tipo' => 'guia', 'titulo' => 'Fuera', 'estado' => 'sin_asignar', 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $ana = $this->usuario('operaciones');

        $this->actingAs($ana)->put("/programas/{$programa->id}", $this->formulario($programa))->assertSessionHas('warning', fn ($t) => str_contains($t, 'fuera de las fechas'));
        $this->put("/programas/{$programa->id}", $this->formulario($programa, ['estado' => 'confirmado']))->assertSessionHas('warning', fn ($t) => str_contains($t, 'aún no tiene pasajeros'));
    }
}

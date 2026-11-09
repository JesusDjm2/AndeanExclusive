<?php

namespace Tests\Feature;

use App\Mail\MensajeProveedorMailable;
use App\Models\MensajeProveedor;
use App\Models\Servicio;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MensajesProveedorTest extends TestCase
{
    private const RESERVAS = 'reservas@andeanexclusivetours.com';

    private function servicio(string $estado = 'pendiente'): Servicio
    {
        $programa = $this->programa(['estado' => 'confirmado']);
        $programa->paxs()->create(['nombre' => 'Elena Ruiz', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES']);

        return $programa->servicios()->create([
            'fecha' => '2030-05-11', 'hora' => '09:00', 'tipo' => 'guia', 'titulo' => 'Guía Machu Picchu',
            'proveedor_id' => $this->proveedor()->id, 'estado' => $estado, 'moneda' => 'USD', 'costo_por' => 'grupo',
        ]);
    }

    private function envio(string $tipo, array $cambios = []): array
    {
        return $cambios + ['tipo' => $tipo, 'para' => 'proveedor@example.com', 'asunto' => 'Asunto de prueba', 'cuerpo' => 'Texto de prueba', 'con_orden' => '0'];
    }

    public function test_reservas_solicita_la_reserva_desde_reservas_y_queda_en_el_hilo(): void
    {
        Mail::fake();
        $servicio = $this->servicio();
        $this->actingAs($this->usuario('reservas'));

        $this->get("/servicios/{$servicio->id}/mensaje")->assertOk()
            ->assertSee('Solicitud de reserva · ' . $servicio->programa->codigo, false)
            ->assertSee('Elena Ruiz')->assertSee(self::RESERVAS);

        $this->post("/servicios/{$servicio->id}/mensaje", $this->envio('solicitud'))->assertRedirect("/servicios/{$servicio->id}/mensaje")->assertSessionHas('success');

        Mail::assertSent(MensajeProveedorMailable::class, function (MensajeProveedorMailable $m) {
            return $m->hasTo('proveedor@example.com') && $m->hasBcc(self::RESERVAS) && $m->envelope()->replyTo[0]->address === self::RESERVAS;
        });
        $this->assertSame('solicitado', $servicio->fresh()->estado);
        $this->assertDatabaseHas('mensajes_proveedor', ['servicio_id' => $servicio->id, 'tipo' => 'solicitud', 'direccion' => 'enviado', 'error' => null]);
        $this->assertTrue($servicio->eventos()->where('detalle', 'like', 'Solicitud de reserva enviada a proveedor@example.com%')->exists());
    }

    public function test_confirmar_adjunta_la_orden_y_cancelar_cancela_el_servicio(): void
    {
        Mail::fake();
        $servicio = $this->servicio('solicitado');
        $this->actingAs($this->usuario('reservas'));

        $this->post("/servicios/{$servicio->id}/mensaje", $this->envio('confirmacion', ['con_orden' => '1']))->assertSessionHas('success');
        Mail::assertSent(MensajeProveedorMailable::class, fn (MensajeProveedorMailable $m) => count($m->attachments()) === 1);
        $this->assertNotNull($servicio->fresh()->orden_enviada_at);
        $this->assertSame('solicitado', $servicio->fresh()->estado, 'Confirmar al proveedor no da por confirmado el servicio: falta su respuesta');

        $this->post("/servicios/{$servicio->id}/mensaje", $this->envio('cancelacion'))->assertSessionHas('success');
        $this->assertSame('cancelado', $servicio->fresh()->estado);
    }

    public function test_la_respuesta_del_proveedor_actualiza_el_servicio(): void
    {
        $servicio = $this->servicio('solicitado');
        $this->actingAs($this->usuario('reservas'));

        $this->post("/servicios/{$servicio->id}/respuesta", ['resultado' => 'confirmado'])->assertSessionHasErrors('confirmacion');
        $this->post("/servicios/{$servicio->id}/respuesta", ['resultado' => 'confirmado', 'confirmacion' => 'MP-778'])->assertSessionHas('success');
        $this->assertSame('confirmado', $servicio->fresh()->estado);
        $this->assertSame('MP-778', $servicio->fresh()->confirmacion);

        $this->post("/servicios/{$servicio->id}/respuesta", ['resultado' => 'sin_disponibilidad', 'detalle' => 'Sin guías ese día'])->assertSessionHas('success');
        $this->assertSame('pendiente', $servicio->fresh()->estado);
        $this->assertNull($servicio->fresh()->confirmacion);
        $this->assertSame(2, MensajeProveedor::where('servicio_id', $servicio->id)->where('direccion', 'recibido')->count());
    }

    public function test_la_bandeja_muestra_lo_que_espera_respuesta_y_las_demas_areas_solo_consultan(): void
    {
        Mail::fake();
        $servicio = $this->servicio();
        $this->actingAs($this->usuario('reservas'))->post("/servicios/{$servicio->id}/mensaje", $this->envio('solicitud'));

        $this->get('/mensajes-proveedores')->assertOk()->assertSee('Esperando respuesta')->assertSee('Guía Machu Picchu')->assertSee('Asunto de prueba');
        $this->get("/proveedors/{$servicio->proveedor_id}")->assertOk()->assertSee('Mensajes con el proveedor')->assertSee('Asunto de prueba');

        $this->actingAs($this->usuario('contabilidad'));
        $this->get('/mensajes-proveedores')->assertOk();
        $this->get("/servicios/{$servicio->id}/mensaje")->assertOk()->assertDontSee('Enviar al proveedor');
        $this->post("/servicios/{$servicio->id}/mensaje", $this->envio('consulta'))->assertForbidden();
        $this->post("/servicios/{$servicio->id}/respuesta", ['resultado' => 'otro'])->assertForbidden();
    }

    public function test_sin_proveedor_no_se_puede_escribir(): void
    {
        $servicio = $this->servicio();
        $servicio->update(['proveedor_id' => null, 'estado' => 'sin_asignar']);
        $this->actingAs($this->usuario('reservas'));

        $this->get("/servicios/{$servicio->id}/mensaje")->assertOk()->assertSee('no tiene proveedor');
        $this->post("/servicios/{$servicio->id}/mensaje", $this->envio('solicitud'))->assertSessionHas('error');
        $this->assertSame(0, MensajeProveedor::count());
    }
}

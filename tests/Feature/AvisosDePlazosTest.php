<?php

namespace Tests\Feature;

use App\Models\Aviso;
use App\Models\Consulta;
use App\Models\Programa;
use App\Models\Servicio;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AvisosDePlazosTest extends TestCase
{
    private function correr(): void
    {
        $this->artisan('avisos:plazos')->assertSuccessful();
    }

    private function confirmado(array $datos = []): Programa
    {
        $programa = $this->programa($datos + ['estado' => 'confirmado', 'inicio' => now()->addDays(100)->toDateString(), 'fin' => now()->addDays(106)->toDateString()]);
        $programa->paxs()->create(['nombre' => 'Elena', 'fecha_nacimiento' => '1982-03-14', 'pais' => 'ES']);

        return $programa;
    }

    public function test_avisa_del_deposito_vencido_y_no_lo_repite(): void
    {
        $programa = $this->confirmado();
        DB::table('programas')->where('id', $programa->id)->update(['confirmado_at' => now()->subDays(10)]);

        $this->correr();
        $this->correr();

        $aviso = Aviso::where('clave', "plazo:deposito:{$programa->id}")->sole();
        $this->assertSame('contabilidad', $aviso->area);
        $this->assertStringContainsString('Depósito vencido', $aviso->titulo);
        $this->assertSame(1, (int) $aviso->veces, 'Mismo día: no se vuelve a marcar como no visto');
    }

    public function test_insiste_a_los_tres_dias_y_se_cierra_solo_al_resolverse(): void
    {
        $programa = $this->confirmado();
        DB::table('programas')->where('id', $programa->id)->update(['confirmado_at' => now()->subDays(10)]);
        $this->correr();

        $this->travel(4)->days();
        $this->correr();
        $aviso = Aviso::where('clave', "plazo:deposito:{$programa->id}")->sole();
        $this->assertSame(2, (int) $aviso->veces);

        $cobro = $programa->fresh()->cobranza();
        $programa->pagos()->create(['fecha' => now()->toDateString(), 'monto' => $cobro['deposito'], 'tipo' => 'deposito']);
        $this->correr();
        $this->assertNotNull($aviso->fresh()->resuelto_at);
    }

    public function test_avisa_del_saldo_solo_cerca_del_vencimiento(): void
    {
        $lejos = $this->confirmado();
        $cerca = $this->confirmado(['inicio' => now()->addDays(65)->toDateString(), 'fin' => now()->addDays(70)->toDateString()]);
        foreach ([$lejos, $cerca] as $p) {
            $p->pagos()->create(['fecha' => now()->toDateString(), 'monto' => $p->cobranza()['deposito'], 'tipo' => 'deposito']);
        }

        $this->correr();

        $this->assertFalse(Aviso::where('clave', "plazo:saldo:{$lejos->id}")->exists());
        $this->assertTrue(Aviso::where('clave', "plazo:saldo:{$cerca->id}")->where('area', 'contabilidad')->exists());
    }

    public function test_avisa_de_servicios_sin_confirmar_agrupados_por_programa(): void
    {
        $programa = $this->confirmado(['inicio' => now()->toDateString(), 'fin' => now()->addDays(10)->toDateString()]);
        foreach ([1, 2] as $dia) {
            $programa->servicios()->create(['fecha' => now()->addDays($dia)->toDateString(), 'tipo' => 'guia', 'titulo' => "Guía $dia", 'estado' => 'sin_asignar', 'moneda' => 'USD', 'costo_por' => 'grupo']);
        }
        $programa->servicios()->create(['fecha' => now()->addDays(9)->toDateString(), 'tipo' => 'guia', 'titulo' => 'Lejos', 'estado' => 'sin_asignar', 'moneda' => 'USD', 'costo_por' => 'grupo']);

        $this->correr();

        $aviso = Aviso::where('clave', "plazo:servicios:{$programa->id}")->sole();
        $this->assertSame('reservas', $aviso->area);
        $this->assertStringContainsString('2 servicios', $aviso->detalle);
    }

    public function test_avisa_de_proveedores_sin_pagar_tareas_vencidas_y_consultas_sin_atender(): void
    {
        $programa = $this->confirmado(['inicio' => now()->subDays(5)->toDateString(), 'fin' => now()->addDays(2)->toDateString()]);
        Servicio::create(['programa_id' => $programa->id, 'fecha' => now()->subDay()->toDateString(), 'tipo' => 'guia', 'titulo' => 'Prestado', 'proveedor_id' => $this->proveedor()->id, 'estado' => 'confirmado', 'confirmacion' => 'A', 'costo' => 100, 'moneda' => 'USD', 'costo_por' => 'grupo']);
        $programa->tareas()->create(['titulo' => 'Vencida', 'fecha_limite' => now()->subDay()->toDateString()]);
        $vieja = Consulta::create(['nombre' => 'Vieja']);
        DB::table('consultas')->where('id', $vieja->id)->update(['created_at' => now()->subDays(2)]);
        Consulta::create(['nombre' => 'Reciente']);

        $this->correr();

        $this->assertTrue(Aviso::where('clave', 'plazo:proveedores')->where('area', 'contabilidad')->exists());
        $this->assertTrue(Aviso::where('clave', "plazo:tareas:{$programa->id}")->where('area', 'operaciones')->exists());
        $this->assertTrue(Aviso::where('clave', "plazo:consulta:{$vieja->id}")->where('area', 'operaciones')->exists());
        $this->assertSame(1, Aviso::where('clave', 'like', 'plazo:consulta:%')->count());
    }

    public function test_el_enlace_de_cada_aviso_abre_y_lo_resuelto_antiguo_se_borra(): void
    {
        $programa = $this->confirmado();
        DB::table('programas')->where('id', $programa->id)->update(['confirmado_at' => now()->subDays(10)]);
        $this->correr();
        $aviso = Aviso::where('clave', 'like', 'plazo:%')->firstOrFail();

        $this->actingAs($this->usuario('contabilidad'))->get("/notificaciones/{$aviso->id}/abrir")->assertRedirectContains('tab=pagos');

        DB::table('avisos')->where('id', $aviso->id)->update(['resuelto_at' => now()->subDays(200)]);
        DB::table('programas')->where('id', $programa->id)->update(['estado' => 'cancelado']);
        $this->correr();
        $this->assertFalse(Aviso::whereKey($aviso->id)->exists());
    }

    public function test_la_tarea_esta_programada(): void
    {
        $this->artisan('schedule:list')->expectsOutputToContain('avisos:plazos')->assertSuccessful();
    }
}

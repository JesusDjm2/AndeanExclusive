<?php

namespace Tests\Feature;

use App\Models\Programa;
use App\Models\TarifarioServicio;
use Tests\TestCase;

/**
 * Comisión por servicio: cada fila del itinerario guarda la comisión que trae el Tarifario Maestro (o queda vacía y usa
 * la del programa), para que la Hoja de Costeo calcule neto + comisión + IGV como la hoja de Aracari.
 */
class ComisionPorServicioTest extends TestCase
{
    private function servicio(array $datos = []): array
    {
        return $datos + [
            'fecha' => '2030-05-11', 'tipo' => 'traslado', 'titulo' => 'Traslado aeropuerto', 'estado' => 'sin_asignar',
            'costo_estimado' => '18.80', 'moneda' => 'USD', 'costo_por' => 'persona', 'visible_cliente' => '1',
        ];
    }

    public function test_cada_servicio_guarda_su_comision_y_si_lleva_igv(): void
    {
        $programa = $this->programa();
        $this->actingAs($this->usuario('operaciones'));

        $this->put("/programas/{$programa->id}", $this->formulario($programa, [
            '_itinerario' => 1,
            'comision_defecto' => '11',
            'servicios' => [
                $this->servicio(['tarifario_codigo' => 'APTHTL', 'comision_pct' => '11', 'igv_comision' => '1']),
                $this->servicio(['titulo' => 'Propina maleteros', 'tipo' => 'otro', 'costo_estimado' => '2', 'tarifario_codigo' => 'PROP-MALETEROS', 'comision_pct' => '0', 'igv_comision' => '0']),
                $this->servicio(['titulo' => 'Servicio escrito a mano', 'tipo' => 'otro', 'comision_pct' => '']),
            ],
        ]))->assertSessionHasNoErrors();

        $filas = $programa->servicios()->orderBy('id')->get();
        $this->assertSame(['11.00', '0.00', null], $filas->pluck('comision_pct')->all());
        $this->assertSame([true, false, true], $filas->pluck('igv_comision')->all());
        $this->assertEquals(11, $programa->fresh()->comision_defecto);
    }

    public function test_la_comision_por_defecto_se_conserva_si_el_formulario_no_la_envia(): void
    {
        $programa = $this->programa();
        $programa->update(['comision_defecto' => 13]);
        $this->actingAs($this->usuario('operaciones'));

        // Sin la Hoja de Costeo en pantalla (usuario sin cotización) el campo no llega.
        $this->put("/programas/{$programa->id}", $this->formulario($programa))->assertSessionHasNoErrors();

        $this->assertEquals(13, $programa->fresh()->comision_defecto);
    }

    public function test_el_desglose_del_tarifario_trae_la_comision_de_cada_parte(): void
    {
        $this->seed(\Database\Seeders\TarifarioAracari2027Seeder::class);
        $this->actingAs($this->usuario('operaciones'));

        $partes = collect($this->getJson(route('tarifario-maestro.componentes', [
            TarifarioServicio::where('codigo', 'APTHTL')->value('id'), 'adultos' => 2,
        ]))->assertOk()->json('partes'));

        // Transporte, representante y agua comisionan 11 % + IGV; la propina de maleteros no comisiona.
        $this->assertSame([11.0, 11.0, 11.0, 0.0], $partes->pluck('comision_pct')->map(fn ($c) => (float) $c)->all());
        $this->assertSame([true, true, true, false], $partes->pluck('igv_comision')->all());

        // Como la hoja: 18.80 neto + 2.07 comisión + 0.37 IGV + 2.00 propina = 23.24.
        $venta = $partes->sum(fn ($p) => $p['costo'] * (1 + $p['comision_pct'] / 100 * ($p['igv_comision'] ? 1.18 : 1)));
        $this->assertEqualsWithDelta(23.24, $venta, 0.01);
    }

    public function test_un_programa_nuevo_empieza_con_comision_de_11(): void
    {
        $this->assertEquals(11, $this->programa()->fresh()->comision_defecto);
    }
}

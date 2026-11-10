<?php

namespace Tests\Feature;

use App\Models\TarifarioServicio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TarifarioServicioTest extends TestCase
{
    public function test_catalogo_maestro_calcula_escala_de_pasajeros_y_feriados(): void
    {
        $this->seed(\Database\Seeders\TarifarioAracari2027Seeder::class);

        $tour = TarifarioServicio::where('codigo', 'HINARC')->firstOrFail();

        // 1 pasajero normal: $152.78 (Catálogo Maestro contiene costos netos de proveedor: comisión 0%)
        $calc1 = $tour->calcularFinanciero(1, false);
        $this->assertEquals(152.78, $calc1['costo_neto']);
        $this->assertEquals(0.00, $calc1['comision_monto']);
        $this->assertEquals(0.00, $calc1['igv_monto']);
        $this->assertEquals(152.78, $calc1['total_venta']);

        // 4 pasajeros normal: $59.56
        $calc4 = $tour->calcularFinanciero(4, false);
        $this->assertEquals(59.56, $calc4['costo_neto']);
        $this->assertEquals(0.00, $calc4['comision_monto']);
        $this->assertEquals(0.00, $calc4['igv_monto']);
        $this->assertEquals(59.56, $calc4['total_venta']);

        // 4 pasajeros feriado: $75.81
        $calc4Feriado = $tour->calcularFinanciero(4, true);
        $this->assertEquals(75.81, $calc4Feriado['costo_neto']);
    }

    public function test_boletos_oficiales_calculan_adulto_y_nino(): void
    {
        $this->seed(\Database\Seeders\TarifarioAracari2027Seeder::class);

        $btg = TarifarioServicio::where('codigo', 'BTG')->firstOrFail();
        $this->assertEquals(39.39, $btg->calcularCosto(1, false, 'adulto'));
        $this->assertEquals(21.21, $btg->calcularCosto(1, false, 'nino'));

        $catedral = TarifarioServicio::where('codigo', 'ENT-CATEDRAL')->firstOrFail();
        $this->assertEquals(12.12, $catedral->calcularCosto(1, false, 'adulto'));
        $this->assertEquals(6.06, $catedral->calcularCosto(1, false, 'nino'));

        $consettur = TarifarioServicio::where('codigo', 'BUS-CONSETTUR-RT')->firstOrFail();
        $this->assertEquals(24.00, $consettur->calcularCosto(1, false, 'adulto'));
        $this->assertEquals(15.00, $consettur->calcularCosto(1, false, 'nino'));
    }

    public function test_vista_catalogo_maestro_carga_para_usuario_autenticado(): void
    {
        $this->seed(\Database\Seeders\TarifarioAracari2027Seeder::class);
        $user = $this->usuario('operaciones');

        $this->actingAs($user)
            ->get(route('tarifario-maestro.index'))
            ->assertOk()
            ->assertSee('Tarifario Maestro de Servicios')
            ->assertSee('BTG');

        // Filtrado por categoría tours muestra FDAWA y permite buscar HINARC
        $this->actingAs($user)
            ->get(route('tarifario-maestro.index', ['cat' => 'tour']))
            ->assertOk()
            ->assertSee('FDAWA');

        $this->actingAs($user)
            ->get(route('tarifario-maestro.index', ['cat' => 'tour', 'q' => 'HINARC']))
            ->assertOk()
            ->assertSee('HINARC');

        $tour = TarifarioServicio::where('codigo', 'FDAWA')->firstOrFail();

        $this->actingAs($user)
            ->get(route('tarifario-maestro.show', $tour))
            ->assertOk()
            ->assertSee('Valle Sagrado')
            ->assertSee('Simulador Financiero');
    }

    public function test_api_cotizar_y_buscar_retornan_json(): void
    {
        $this->seed(\Database\Seeders\TarifarioAracari2027Seeder::class);
        $user = $this->usuario('operaciones');

        // Test API Cotizar
        $response = $this->actingAs($user)
            ->getJson(route('tarifario-maestro.cotizar', ['codigo' => 'FDAWA', 'pax' => 2]))
            ->assertOk()
            ->assertJsonPath('ok', true)
            ->assertJsonPath('codigo', 'FDAWA');

        $this->assertArrayHasKey('calculo', $response->json());

        // Test API Buscar
        $busqueda = $this->actingAs($user)
            ->getJson(route('tarifario-maestro.buscar', ['q' => 'Pisac', 'pax' => 2]))
            ->assertOk();

        $items = $busqueda->json('items');
        $this->assertNotEmpty($items);
        $this->assertEquals('FDAWA', $items[0]['codigo']);
    }

    public function test_programa_soporta_adultos_y_ninos_y_cotiza_tarifas_diferenciadas(): void
    {
        $this->seed(\Database\Seeders\TarifarioAracari2027Seeder::class);
        $user = $this->usuario('operaciones');

        // 1. Crear un programa con 2 adultos y 1 niño usando el helper del test
        $programa = $this->programa([
            'nombre' => 'Viaje Familiar Valle y Machu Picchu',
            'estado' => 'cotizacion',
            'moneda' => 'USD',
            'cantidad_adultos' => 2,
            'cantidad_ninos' => 1,
            'precioAdulto' => 500.00,
            'precioChild' => 350.00,
            'porcentaje_ganancia' => 20.00,
        ]);

        // Verifica que sincroniza cantidad_pax = 3
        $this->assertEquals(2, $programa->cantidad_adultos);
        $this->assertEquals(1, $programa->cantidad_ninos);
        $this->assertEquals(3, $programa->cantidad_pax);
        $this->assertEquals(3, $programa->pagantes());

        // Presupuesto con fórmula adultos + niños
        $pres = $programa->presupuesto();
        $this->assertEquals(2, $pres['adultos']);
        $this->assertEquals(1, $pres['ninos']);
        $this->assertEquals(1000.00, $pres['subtotalAdultos']); // 2 * 500
        $this->assertEquals(350.00, $pres['subtotalNinos']);    // 1 * 350
        $this->assertEquals(1350.00, $pres['subtotal']);

        // 2. Probar API de búsqueda para 2 adultos y 1 niño en boleto BTG
        $busqueda = $this->actingAs($user)
            ->getJson(route('tarifario-maestro.buscar', ['q' => 'BTG', 'adultos' => 2, 'ninos' => 1]))
            ->assertOk();

        $btgItem = collect($busqueda->json('items'))->firstWhere('codigo', 'BTG');
        $this->assertNotNull($btgItem);
        // Costo ponderado: (2 * 39.39 + 1 * 21.21) / 3 = 99.99 / 3 = 33.33
        $this->assertEquals(33.33, $btgItem['costo_pax']);
        $this->assertEquals(39.39, $btgItem['precio_adulto']);
        $this->assertEquals(21.21, $btgItem['precio_nino']);
    }

    public function test_componentes_desglosan_el_servicio_para_el_itinerario(): void
    {
        $this->seed(\Database\Seeders\TarifarioAracari2027Seeder::class);
        $user = $this->usuario('operaciones');
        $antes = TarifarioServicio::query()->get(['codigo', 'desglose', 'precios_escalonados'])->toJson();
        $partes = fn (string $codigo, int $adultos = 2) => $this->actingAs($user)
            ->getJson(route('tarifario-maestro.componentes', [TarifarioServicio::where('codigo', $codigo)->value('id'), 'adultos' => $adultos]))
            ->assertOk();

        // Full Day Valle (FDAWA): sincronizado con desglose oficial (transporte, viático, guía, snacks, bastones).
        $fdawa = $partes('FDAWA');
        $fdawa->assertJsonPath('total', 122.75);
        $lista = collect($fdawa->json('partes'));
        $this->assertEqualsWithDelta(122.75, $lista->where('incluido', true)->sum('costo'), 0.001);
        $this->assertSame(['traslado', 'viatico', 'guia', 'alimentacion', 'componente', 'alimentacion', 'guia'], $lista->pluck('tipo')->all());
        $this->assertSame([true, false, false, false, false, false, false], $lista->pluck('visible_cliente')->all());

        // Machu Picchu: entrada y bus caben en la tarifa; lo que sobra es del guía y la suma da la tarifa exacta.
        $mapi = $partes('FMAPI');
        $mapi->assertJsonPath('adicionales', false);
        $lista = collect($mapi->json('partes'));
        $this->assertSame(['MP-INC', 'BUS-CONSETTUR-RT'], $lista->pluck('codigo')->filter()->values()->all());
        $this->assertEqualsWithDelta($mapi->json('total'), $lista->sum('costo'), 0.001);

        // Traslado aeropuerto (hoja TRFS): transporte, representante y agua suman la tarifa; la propina va aparte.
        $apthtl = $partes('APTHTL');
        $lista = collect($apthtl->json('partes'));
        $this->assertSame([10.0, 7.5, 1.3, 2.0], $lista->pluck('costo')->map(fn ($c) => (float) $c)->all());
        $this->assertSame([true, true, true, false], $lista->pluck('incluido')->all());
        // El representante es su propio tipo de servicio y lo reconoce el rubro «Representante» del proveedor.
        $this->assertSame('representante', $lista[1]['tipo']);
        $this->assertSame(['representante'], \App\Models\Servicio::tiposDeRubro('Representante'));
        $this->assertEqualsWithDelta(18.80, $lista->where('incluido', true)->sum('costo'), 0.001);
        // Desde 9 pax entra el 2.º representante y en feriado el representante se paga doble.
        $this->assertContains('2.º representante · Traslado Aeropuerto Cusco <-> Hotel Cusco',
            collect($partes('APTHTL', 9)->json('partes'))->pluck('nombre'));
        $feriado = $this->actingAs($user)->getJson(route('tarifario-maestro.componentes',
            [TarifarioServicio::where('codigo', 'APTHTL')->value('id'), 'adultos' => 2, 'feriado' => 'true']))->json();
        $this->assertEquals(15.0, $feriado['partes'][1]['costo']);
        $this->assertEqualsWithDelta(26.30, collect($feriado['partes'])->where('incluido', true)->sum('costo'), 0.001);

        // Los códigos alternos de la hoja también encuentran el traslado.
        $this->actingAs($user)->getJson(route('tarifario-maestro.buscar', ['q' => 'URUAPT']))
            ->assertOk()->assertJsonPath('items.0.codigo', 'APTOLL');

        // Un boleto se agrega entero, con su precio.
        $lista = collect($partes('BTG')->json('partes'));
        $this->assertCount(1, $lista);
        $this->assertSame('entrada', $lista[0]['tipo']);
        $this->assertEquals(39.39, $lista[0]['costo']);

        // Consultar el desglose no modifica el catálogo.
        $this->assertSame($antes, TarifarioServicio::query()->get(['codigo', 'desglose', 'precios_escalonados'])->toJson());
    }
}

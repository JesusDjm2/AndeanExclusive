<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\Tarifa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TarifasFijasYCotizadorTest extends TestCase
{
    public function test_proveedor_puede_tener_tarifas_fijas_todo_el_ano(): void
    {
        $proveedor = $this->proveedor('Transporte Los Andes Test');

        $tarifaFija = Tarifa::create([
            'tipo' => 'proveedor',
            'proveedor_id' => $proveedor->id,
            'concepto' => 'Traslado Aeropuerto → Hotel',
            'unidad_detalle' => 'Van H1 (1-6 pax)',
            'es_fija' => true,
            'desde' => null,
            'hasta' => null,
            'precio' => 35.00,
            'moneda' => 'USD',
            'por' => 'grupo',
        ]);

        $this->assertTrue($tarifaFija->es_fija);
        $this->assertEquals('Tarifa fija (Todo el año)', $tarifaFija->temporada_texto);
        $this->assertTrue($tarifaFija->es_vigente);

        // Scope vigenteEl con fecha cualquiera
        $encontrada = Tarifa::query()->vigenteEl('2026-07-15')
            ->where('proveedor_id', $proveedor->id)
            ->first();

        $this->assertNotNull($encontrada);
        $this->assertEquals('Traslado Aeropuerto → Hotel', $encontrada->concepto);
    }

    public function test_sugerir_tarifas_de_proveedor_json(): void
    {
        $this->actingAs($this->usuario('operaciones'));

        $proveedor = $this->proveedor('Guía Juan Pérez Test');

        Tarifa::create([
            'proveedor_id' => $proveedor->id,
            'concepto' => 'Guía Día Completo',
            'es_fija' => true,
            'precio' => 90.00,
            'moneda' => 'USD',
            'por' => 'grupo',
        ]);

        $response = $this->getJson(route('tarifas.sugerir', ['proveedor_id' => $proveedor->id]));
        $response->assertOk()
            ->assertJsonPath('tarifas.0.concepto', 'Guía Día Completo')
            ->assertJsonPath('tarifas.0.precio', 90)
            ->assertJsonPath('tarifas.0.es_fija', true);
    }

    public function test_redireccion_retorno_proveedor_y_permisos(): void
    {
        $this->actingAs($this->usuario('reservas'));

        $proveedor = $this->proveedor('Restaurante Valle Sagrado Test');

        $response = $this->post(route('tarifas.store'), [
            'tipo' => 'proveedor',
            'proveedor_id' => $proveedor->id,
            'concepto' => 'Almuerzo Buffet',
            'es_fija' => '1',
            'precio' => '25.00',
            'moneda' => 'USD',
            'por' => 'persona',
            'retorno' => 'proveedor',
        ]);

        $response->assertRedirect(route('proveedors.show', $proveedor->id));
        $this->assertDatabaseHas('tarifas', [
            'proveedor_id' => $proveedor->id,
            'concepto' => 'Almuerzo Buffet',
            'es_fija' => 1,
            'por' => 'persona',
        ]);
    }
}

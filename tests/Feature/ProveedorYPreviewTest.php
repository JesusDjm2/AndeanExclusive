<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Movimiento;
use App\Models\Programa;
use App\Models\Proveedor;
use App\Models\Servicio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProveedorYPreviewTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = $this->usuario('administrador');
    }

    public function test_proveedores_index_loads_and_can_filter_by_categoria_and_search(): void
    {
        $catHoteles = Categoria::create(['nombre' => 'Hoteles']);
        $catGuias = Categoria::create(['nombre' => 'Guías']);

        $prov1 = Proveedor::create([
            'nombre' => 'Hotel Belmond Sanctuary',
            'categoria_id' => $catHoteles->id,
            'telefono' => '984123456',
            'correo' => 'reservas@belmond.com',
            'ruc' => '20123456789',
        ]);

        $prov2 = Proveedor::create([
            'nombre' => 'Juan Guía Andino',
            'categoria_id' => $catGuias->id,
            'telefono' => '984654321',
            'correo' => 'juan@guia.com',
            'ruc' => '10123456789',
        ]);

        // Sin filtros: muestra ambos
        $response = $this->actingAs($this->admin)->get(route('proveedors.index'));
        $response->assertOk();
        $response->assertSee('Hotel Belmond Sanctuary');
        $response->assertSee('Juan Guía Andino');

        // Filtrado por categoría
        $responseCat = $this->actingAs($this->admin)->get(route('proveedors.index', ['categoria_id' => $catHoteles->id]));
        $responseCat->assertOk();
        $responseCat->assertSee('Hotel Belmond Sanctuary');
        $responseCat->assertDontSee('Juan Guía Andino');

        // Búsqueda por texto (RUC o nombre)
        $responseSearch = $this->actingAs($this->admin)->get(route('proveedors.index', ['q' => '984654321']));
        $responseSearch->assertOk();
        $responseSearch->assertSee('Juan Guía Andino');
        $responseSearch->assertDontSee('Hotel Belmond Sanctuary');
    }

    public function test_movimientos_y_cuentas_render_programa_codigo_preview(): void
    {
        $cliente = Cliente::create([
            'nombre' => 'Eleanor Vance',
            'email' => 'eleanor@example.com',
        ]);

        $programa = Programa::create([
            'nombre' => 'Cusco Mágico Exclusivo',
            'codigo' => 'AET-2026-0099',
            'cliente_id' => $cliente->id,
            'inicio' => now()->addDays(5)->toDateString(),
            'fin' => now()->addDays(10)->toDateString(),
            'estado' => 'confirmado',
        ]);

        $movimiento = Movimiento::create([
            'usuario' => 'Admin',
            'area' => 'Operaciones',
            'modulo' => 'Programa',
            'verbo' => 'actualizó',
            'titulo' => 'Cusco Mágico Exclusivo',
            'programa_id' => $programa->id,
            'programa_codigo' => $programa->codigo,
        ]);

        // Vista movimientos
        $responseMov = $this->actingAs($this->admin)->get(route('movimientos.index'));
        $responseMov->assertOk();
        $responseMov->assertSee('AET-2026-0099');
        $responseMov->assertSee('ae-prog-card-preview');
        $responseMov->assertSee('Eleanor Vance');

        // Vista cuentas por pagar
        $cat = Categoria::create(['nombre' => 'Transporte']);
        $prov = Proveedor::create([
            'nombre' => 'Transportes Cusco VIP',
            'categoria_id' => $cat->id,
            'telefono' => '984000111',
            'correo' => 'trans@vip.com',
        ]);

        Servicio::create([
            'programa_id' => $programa->id,
            'proveedor_id' => $prov->id,
            'titulo' => 'Traslado Aeropuerto Hotel',
            'fecha' => now()->addDays(5)->toDateString(),
            'estado' => 'confirmado',
            'costo' => 150.00,
            'moneda' => 'USD',
            'costo_por' => 'grupo',
        ]);

        $responseCxp = $this->actingAs($this->admin)->get(route('cuentas.index'));
        $responseCxp->assertOk();
        $responseCxp->assertSee('AET-2026-0099');
        $responseCxp->assertSee('ae-prog-card-preview');
    }
}

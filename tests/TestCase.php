<?php

namespace Tests;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Habitacion;
use App\Models\Hotel;
use App\Models\Programa;
use App\Models\Proveedor;
use App\Models\User;
use Database\Seeders\AnioSeeder;
use Database\Seeders\MesSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function beforeRefreshingDatabase(): void
    {
        // RefreshDatabase borra todas las tablas: nunca debe alcanzar la base de trabajo.
        if (! str_ends_with((string) DB::getDatabaseName(), '_test')) {
            throw new RuntimeException('Las pruebas solo corren en una base que termine en _test.');
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([RolePermissionSeeder::class, MesSeeder::class, AnioSeeder::class]);
    }

    protected function usuario(string $rol, ?string $nombre = null): User
    {
        $user = User::factory()->create(['name' => $nombre ?? ucfirst($rol) . ' Prueba']);
        $user->assignRole($rol);

        return $user->fresh();
    }

    protected function proveedor(string $nombre = 'Transportes Prueba'): Proveedor
    {
        $categoria = Categoria::firstOrCreate(['nombre' => 'Categoría de prueba']);

        return Proveedor::create(['nombre' => $nombre, 'categoria_id' => $categoria->id, 'telefono' => '+51 900 000 000', 'correo' => 'proveedor@example.com']);
    }

    protected function habitacion(string $tipo = 'Doble', int $capacidad = 2): Habitacion
    {
        $hotel = Hotel::firstOrCreate(['nombre' => 'Hotel de prueba'], ['direccion' => 'Cusco', 'telefono' => '+51 900 000 001', 'correo' => 'hotel@example.com']);

        return Habitacion::create(['hotel_id' => $hotel->id, 'tipo' => $tipo, 'capacidad' => $capacidad]);
    }

    protected function programa(array $datos = []): Programa
    {
        $cliente = Cliente::create(['tipo' => 'directo', 'nombre' => 'Cliente Prueba', 'email' => 'cliente@example.com']);

        return Programa::create($datos + [
            'nombre' => 'Viaje de prueba ' . Str::random(4), 'estado' => 'cotizacion', 'cliente_id' => $cliente->id, 'lang' => 'es',
            'inicio' => '2030-05-10', 'fin' => '2030-05-16', 'precioAdulto' => 1000, 'precioChild' => 800, 'porcentaje_ganancia' => 20,
        ]);
    }

    /** Datos del formulario de programas: lo que envía la pantalla al guardar. */
    protected function formulario(Programa $programa, array $cambios = []): array
    {
        return $cambios + [
            'nombre' => $programa->nombre, 'codigo' => $programa->codigo, 'estado' => $programa->estado, 'lang' => $programa->lang,
            'cliente_modo' => 'existente', 'cliente_id' => $programa->cliente_id, 'inicio' => $programa->inicio, 'fin' => $programa->fin,
            'precioAdulto' => $programa->precioAdulto, 'precioChild' => $programa->precioChild, 'porcentaje_ganancia' => $programa->porcentaje_ganancia,
            'paxs' => $programa->paxs()->orderBy('id')->get()->map(fn ($p) => [
                'id' => $p->id, 'nombre' => $p->nombre, 'fecha_nacimiento' => $p->fecha_nacimiento?->toDateString(), 'pais' => $p->pais,
                'pasaporte' => $p->pasaporte, 'pasaporte_vencimiento' => $p->pasaporte_vencimiento?->toDateString(),
            ])->all(),
        ];
    }

    /** Peticiones sin sesión desde el enlace del cliente: fuera de consola, como en la web real. */
    protected function comoVisitaWeb(): static
    {
        (new \ReflectionProperty($this->app, 'isRunningInConsole'))->setValue($this->app, false);

        return $this->withoutMiddleware(ValidateCsrfToken::class);
    }
}

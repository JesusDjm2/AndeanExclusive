<?php

namespace Database\Seeders;

use App\Models\Agente;
use App\Models\Anio;
use App\Models\Categoria;
use App\Models\Habitacion;
use App\Models\HabitacionFecha;
use App\Models\Hotel;
use App\Models\Mes;
use App\Models\Pax;
use App\Models\Programa;
use App\Models\Proveedor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProgramaSeeder extends Seeder
{
    /**
     * Borra todos los programas (cascade: paxs, pivotes, fechas de habitación, etc.)
     * y crea 24 registros de demostración con hoteles, habitaciones, proveedores y paxes.
     */
    public function run(): void
    {
        $this->call([
            MesSeeder::class,
            AnioSeeder::class,
        ]);

        $categoriaDemo = Categoria::query()->firstOrCreate(
            ['nombre' => 'Demo operaciones'],
            ['nombre' => 'Demo operaciones']
        );

        $proveedores = $this->ensureProveedoresDemo($categoriaDemo->id);
        $hoteles = $this->ensureHotelesYHabitacionesDemo();

        // Limpia filas colgadas (p. ej. borrados previos con FK desactivadas en MySQL).
        $idsPrograma = Programa::query()->pluck('id');
        if ($idsPrograma->isNotEmpty()) {
            Pax::query()->whereNotIn('programa_id', $idsPrograma)->delete();
            HabitacionFecha::query()->whereNotIn('programa_id', $idsPrograma)->delete();
            foreach (['programa_proveedor', 'hotel_programa', 'habitacion_programa', 'agente_programa'] as $tabla) {
                DB::table($tabla)->whereNotIn('programa_id', $idsPrograma)->delete();
            }
        }
        Programa::query()->delete();

        $meses = Mes::query()->orderBy('numero')->get()->keyBy('numero');
        $anios = Anio::query()->orderBy('anio')->get()->values();
        $agenteId = Agente::query()->value('id');

        $nombresBase = [
            'Machu Picchu & Valle Sagrado',
            'Líneas de Nazca y Huacachina',
            'Selva Amazónica Tambopata',
            'Lago Titicaca y Uros',
            'Arequipa y Cañón del Colca',
            'Cusco ciudad imperial',
            'Circuito Sur: Puno y Cusco',
            'Aventura en Huaraz',
            'Trujillo y Chan Chan',
            'Iquitos crucero Amazonas',
            'Paracas y Ballestas',
            'Ruta del café en Chanchamayo',
            'Lima gastronómica express',
            'Vinicunca montaña de colores',
            'Salkantay trek preview',
            'Caminata a Choquequirao',
            'Islas Ballestas premium',
            'Reserva Nacional de Paracas',
            'Tour fotográfico Cusco',
            'Luna de miel Perú clásico',
            'Grupo corporativo Lima-Cusco',
            'Familias: Perú con niños',
            'Senior friendly Perú',
            'Último minuto Semana Santa',
        ];

        $alimentaciones = ['Regular', 'Vegetariano', 'Vegano', 'Sin gluten'];
        $nacionalidades = ['Peruana', 'Estadounidense', 'Española', 'Alemana', 'Francesa', 'Mexicana', 'Argentina', 'Chilena'];

        $total = count($nombresBase);
        for ($i = 0; $i < $total; $i++) {
            $n = $i + 1;
            $mesNum = ($i % 12) + 1;
            $mes = $meses->get($mesNum);
            $anio = $anios->isEmpty() ? null : $anios->get($i % $anios->count());

            $inicio = sprintf('2026-%02d-%02d', $mesNum, min(26, 1 + ($i % 5)));
            $fin = sprintf('2026-%02d-%02d', $mesNum, min(28, 8 + ($i % 5)));

            $programa = Programa::query()->create([
                'nombre' => $nombresBase[$i],
                'codigo' => 'DEMO-' . str_pad((string) $n, 4, '0', STR_PAD_LEFT),
                'email' => 'demo.programa' . $n . '@andeanexclusive.com',
                'inicio' => $inicio,
                'fin' => $fin,
                'lang' => $i % 3 === 0 ? 'en' : 'es',
                'precioAdulto' => (string) (2400 + $n * 25),
                'precioChild' => (string) (1500 + $n * 15),
                'presentacion' => '<p>Programa de demostración <strong>#' . $n . '</strong>. Datos generados por <code>ProgramaSeeder</code>.</p>',
                'anio_id' => $anio?->id,
                'mes_id' => $mes?->id,
            ]);

            if ($agenteId) {
                $programa->agentes()->syncWithoutDetaching([$agenteId]);
            }

            $hotel = $hoteles[$i % $hoteles->count()];
            $hotel->programas()->syncWithoutDetaching([$programa->id]);

            $habitacionesHotel = $hotel->habitaciones()->orderBy('id')->get();
            $elegidas = $habitacionesHotel->take(2);
            if ($elegidas->isEmpty()) {
                continue;
            }

            $programa->habitaciones()->syncWithoutDetaching($elegidas->pluck('id')->all());

            $habPrincipal = $elegidas->first();
            HabitacionFecha::query()->create([
                'programa_id' => $programa->id,
                'habitacion_id' => $habPrincipal->id,
                'fecha_ingreso' => $inicio,
                'fecha_salida' => $fin,
            ]);

            $provCount = $proveedores->count();
            if ($provCount > 0) {
                $ids = [
                    $proveedores[$i % $provCount]->id,
                    $proveedores[($i + 1) % $provCount]->id,
                ];
                $programa->proveedores()->syncWithoutDetaching(array_unique($ids));
            }

            $numPax = 2 + ($i % 3);
            for ($p = 0; $p < $numPax; $p++) {
                $pax = Pax::query()->create([
                    'nombre' => 'Pasajero demo ' . $n . '-' . ($p + 1),
                    'edad' => 18 + (($i + $p) * 7 % 52),
                    'pasaporte' => 'DEMO' . str_pad((string) ($n * 10 + $p), 6, '0', STR_PAD_LEFT),
                    'nacionalidad' => $nacionalidades[($i + $p) % count($nacionalidades)],
                    'alimentacion' => $alimentaciones[($i + $p) % count($alimentaciones)],
                    'programa_id' => $programa->id,
                ]);
                if ($agenteId) {
                    $pax->agentes()->syncWithoutDetaching([$agenteId]);
                }
            }
        }
    }

    private function ensureProveedoresDemo(int $categoriaId): Collection
    {
        $filas = [
            ['nombre' => 'DEMO Transportes Andinos', 'ruc' => '20123456781', 'telefono' => '+51 1 6000001', 'correo' => 'transportes.demo@example.com'],
            ['nombre' => 'DEMO Guías Cusco', 'ruc' => '20123456782', 'telefono' => '+51 84 6000002', 'correo' => 'guias.demo@example.com'],
            ['nombre' => 'DEMO Restaurantes Altura', 'ruc' => '20123456783', 'telefono' => '+51 1 6000003', 'correo' => 'rest.demo@example.com'],
            ['nombre' => 'DEMO Buses Turísticos', 'ruc' => '20123456784', 'telefono' => '+51 1 6000004', 'correo' => 'buses.demo@example.com'],
            ['nombre' => 'DEMO Entradas Culturales', 'ruc' => '20123456785', 'telefono' => '+51 1 6000005', 'correo' => 'entradas.demo@example.com'],
            ['nombre' => 'DEMO Seguros Viaje', 'ruc' => '20123456786', 'telefono' => '+51 1 6000006', 'correo' => 'seguros.demo@example.com'],
            ['nombre' => 'DEMO Equipamiento Trek', 'ruc' => '20123456787', 'telefono' => '+51 1 6000007', 'correo' => 'trek.demo@example.com'],
            ['nombre' => 'DEMO Cruceros Amazonas', 'ruc' => '20123456788', 'telefono' => '+51 65 6000008', 'correo' => 'amazon.demo@example.com'],
        ];

        foreach ($filas as $row) {
            Proveedor::query()->updateOrCreate(
                ['nombre' => $row['nombre']],
                array_merge($row, ['categoria_id' => $categoriaId, 'direccion' => 'Lima, Perú', 'detalles' => 'Proveedor de demostración'])
            );
        }

        return Proveedor::query()->whereIn('nombre', array_column($filas, 'nombre'))->orderBy('id')->get();
    }

    private function ensureHotelesYHabitacionesDemo(): Collection
    {
        $hotelesData = [
            ['nombre' => 'DEMO Hotel Monasterio Cusco', 'telefono' => '+51 84 6000101', 'correo' => 'monasterio.demo@example.com'],
            ['nombre' => 'DEMO Casa Andina Premium Miraflores', 'telefono' => '+51 1 6000102', 'correo' => 'miraflores.demo@example.com'],
            ['nombre' => 'DEMO Libertador Lago Titicaca', 'telefono' => '+51 51 6000103', 'correo' => 'titicaca.demo@example.com'],
            ['nombre' => 'DEMO Inkaterra Machu Picchu', 'telefono' => '+51 84 6000104', 'correo' => 'inkaterra.demo@example.com'],
            ['nombre' => 'DEMO Casa Andina Nazca', 'telefono' => '+51 56 6000105', 'correo' => 'nazca.demo@example.com'],
            ['nombre' => 'DEMO Refugio Amazonas', 'telefono' => '+51 82 6000106', 'correo' => 'amazonas.demo@example.com'],
        ];

        $tiposHabitacion = [
            ['tipo' => 'Matrimonial', 'capacidad' => 2],
            ['tipo' => 'Doble', 'capacidad' => 2],
            ['tipo' => 'Triple', 'capacidad' => 3],
        ];

        foreach ($hotelesData as $hd) {
            $hotel = Hotel::query()->updateOrCreate(
                ['nombre' => $hd['nombre']],
                array_merge($hd, [
                    'ruc' => '20' . str_pad((string) crc32($hd['nombre']), 7, '0', STR_PAD_LEFT),
                    'direccion' => 'Perú',
                    'detalles' => 'Hotel de demostración',
                ])
            );
            foreach ($tiposHabitacion as $th) {
                Habitacion::query()->firstOrCreate(
                    ['hotel_id' => $hotel->id, 'tipo' => $th['tipo']],
                    ['capacidad' => $th['capacidad']]
                );
            }
        }

        return Hotel::query()
            ->whereIn('nombre', array_column($hotelesData, 'nombre'))
            ->orderBy('id')
            ->with('habitaciones')
            ->get();
    }
}

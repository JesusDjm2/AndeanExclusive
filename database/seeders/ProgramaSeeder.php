<?php

namespace Database\Seeders;

use App\Models\Agente;
use App\Models\Anio;
use Carbon\Carbon;
use App\Models\Cliente;
use App\Models\Categoria;
use App\Models\Habitacion;
use App\Models\HabitacionFecha;
use App\Models\Hotel;
use App\Models\Mes;
use App\Models\Pax;
use App\Models\Programa;
use App\Models\Tarea;
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
            foreach (['servicios', 'programa_dias', 'tareas', 'hotel_programa', 'habitacion_programa', 'agente_programa'] as $tabla) {
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

        $dietas = ['ninguna', 'vegetariana', 'vegana', 'sin_gluten'];
        $paises = ['US', 'CA', 'GB', 'DE', 'FR', 'ES', 'MX', 'AU'];
        $agencias = ['Andes Luxury Travel (USA)', 'Inca Journeys UK', 'Viajes Cóndor España'];
        // Clientes de prueba de corridas anteriores (y los que la migración creó desde los correos demo).
        Cliente::query()->where('email', 'like', '%@demo.andeanexclusive.com')
            ->orWhere('email', 'like', 'demo.programa%@andeanexclusive.com')->delete();

        $total = count($nombresBase);
        for ($i = 0; $i < $total; $i++) {
            $n = $i + 1;
            $mesNum = ($i % 12) + 1;
            $mes = $meses->get($mesNum);
            $anio = $anios->isEmpty() ? null : $anios->get($i % $anios->count());

            $inicio = sprintf('2026-%02d-%02d', $mesNum, min(26, 1 + ($i % 5)));
            $fin = sprintf('2026-%02d-%02d', $mesNum, min(28, 8 + ($i % 5)));
            // Los dos últimos, alrededor de hoy: así la hoja de operaciones y el dashboard tienen movimiento.
            if ($i >= $total - 2) {
                $desde = $i === $total - 1 ? -2 : 1;
                $inicio = now()->addDays($desde)->toDateString();
                $fin = now()->addDays($desde + 5)->toDateString();
            }

            $programa = Programa::query()->create([
                'nombre' => $nombresBase[$i],
                'codigo' => 'DEMO-'.str_pad((string) $n, 4, '0', STR_PAD_LEFT),
                // Titular: cada 3er programa viene de una agencia; el resto son clientes directos.
                'cliente_id' => Cliente::query()->create($i % 3 === 2 ? [
                    'tipo' => 'agencia', 'nombre' => 'Agente demo '.$n, 'empresa' => $agencias[intdiv($i, 3) % count($agencias)],
                    'email' => 'agencia'.$n.'@demo.andeanexclusive.com', 'telefono' => '+1 555 010 '.str_pad((string) $n, 4, '0', STR_PAD_LEFT),
                    'pais' => $paises[$i % count($paises)], 'idioma' => 'en', 'origen' => 'agencia',
                ] : [
                    'tipo' => 'directo', 'nombre' => 'Cliente demo '.$n,
                    'email' => 'cliente'.$n.'@demo.andeanexclusive.com', 'telefono' => '+1 555 020 '.str_pad((string) $n, 4, '0', STR_PAD_LEFT),
                    'pais' => $paises[$i % count($paises)], 'idioma' => $i % 3 === 0 ? 'en' : 'es',
                    'origen' => ['web', 'whatsapp', 'recomendacion'][$i % 3],
                ])->id,
                'inicio' => $inicio,
                'fin' => $fin,
                'lang' => $i % 3 === 0 ? 'en' : 'es',
                'precioAdulto' => (string) (2400 + $n * 25),
                'precioChild' => (string) (1500 + $n * 15),
                'porcentaje_ganancia' => '15.00',
                'tipo_cambio' => '3.750',
                'suplemento_individual' => $i % 5 === 0 ? '420.00' : '0',
                'habitaciones_individuales' => $i % 5 === 0 ? 1 : 0,
                'presentacion' => '<p>Programa de demostración <strong>#'.$n.'</strong>. Datos generados por <code>ProgramaSeeder</code>.</p>',
                // Año y mes los asigna el modelo desde la fecha de inicio.
                'estado' => $i >= $total - 2 ? 'confirmado' : ['cotizacion', 'confirmado', 'confirmado', 'cancelado'][$i % 4],
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

            $habPrincipal = $elegidas->first();
            HabitacionFecha::query()->create([
                'programa_id' => $programa->id,
                'habitacion_id' => $habPrincipal->id,
                'cantidad' => 1 + ($i % 3),
                'fecha_ingreso' => $inicio,
                'fecha_salida' => $fin,
            ]);

            $numPax = 2 + ($i % 3);
            for ($p = 0; $p < $numPax; $p++) {
                $pax = Pax::query()->create([
                    'nombre' => 'Pasajero demo '.$n.'-'.($p + 1),
                    'fecha_nacimiento' => \Carbon\Carbon::parse($inicio)->subYears(18 + (($i + $p) * 7 % 52))->subMonths(1 + $p)->toDateString(),
                    'sexo' => ['F', 'M'][($i + $p) % 2],
                    'pais' => $paises[($i + $p) % count($paises)],
                    'pasaporte' => 'DEMO'.str_pad((string) ($n * 10 + $p), 6, '0', STR_PAD_LEFT),
                    // Uno de cada 7 vence antes de los 6 meses del viaje, para ver la alerta.
                    'pasaporte_vencimiento' => \Carbon\Carbon::parse($inicio)->addMonths(($n + $p) % 7 === 0 ? 3 : 30)->toDateString(),
                    'dieta' => $dietas[($i + $p) % count($dietas)],
                    'alergias' => ($i + $p) % 5 === 0 ? 'Maní' : null,
                    'emergencia_nombre' => 'Contacto demo '.$n.'-'.($p + 1),
                    'emergencia_telefono' => '+1 555 030 '.str_pad((string) ($n * 10 + $p), 4, '0', STR_PAD_LEFT),
                    'programa_id' => $programa->id,
                ]);
                if ($agenteId) {
                    $pax->agentes()->syncWithoutDetaching([$agenteId]);
                }
            }

            // Itinerario después de los pasajeros: los costos por persona dependen de cuántos pagan.
            $this->itinerarioDemo($programa, $proveedores, $i);

            // Pagos de ejemplo: los confirmados tienen el depósito (50 %); la mitad, además, el saldo.
            if ($programa->estado === 'confirmado') {
                $totalPrograma = $programa->load('paxs')->presupuesto()['total'];
                $programa->pagos()->create([
                    'fecha' => \Carbon\Carbon::parse($inicio)->subDays(120),
                    'monto' => round($totalPrograma / 2, 2),
                    'tipo' => 'deposito',
                    'metodo' => 'transferencia',
                    'referencia' => 'DEMO-DEP-'.$n,
                ]);
                if ($i % 2 === 0) {
                    $programa->pagos()->create([
                        'fecha' => \Carbon\Carbon::parse($inicio)->subDays(65),
                        'monto' => $totalPrograma - round($totalPrograma / 2, 2),
                        'tipo' => 'saldo',
                        'metodo' => 'tarjeta',
                        'referencia' => 'DEMO-SAL-'.$n,
                    ]);
                }
            }
        }
    }

    /**
     * Itinerario de ejemplo: llegada, días de excursión (con tren y entrada si el programa incluye Machu Picchu),
     * almuerzos y salida; más la lista de tareas estándar en los confirmados.
     * Estados: cotización = por reservar; confirmado = la mayoría confirmados y algunos solicitados.
     */
    private function itinerarioDemo(Programa $programa, Collection $proveedores, int $i): void
    {
        $prov = fn (int $k) => $proveedores->get($k % max(1, $proveedores->count()))?->id;
        $confirmado = $programa->estado === 'confirmado';
        $inicio = Carbon::parse($programa->inicio);
        $dias = $inicio->diffInDays(Carbon::parse($programa->fin));
        $machuPicchu = str_contains($programa->nombre, 'Machu Picchu') || str_contains($programa->nombre, 'Cusco') || $i % 4 === 1;
        $n = 0;
        $estado = function () use ($confirmado, &$n) {
            $n++;

            return $confirmado ? ($n % 5 === 0 ? 'solicitado' : 'confirmado') : 'pendiente';
        };
        // Costos de ejemplo: tipo => [monto, moneda, por grupo o por persona].
        $costos = [
            'traslado' => [45, 'USD', 'grupo'], 'tour' => [120, 'USD', 'grupo'], 'guia' => [80, 'USD', 'grupo'],
            'tren' => [90, 'USD', 'persona'], 'entrada' => [152, 'PEN', 'persona'], 'comida' => [95, 'PEN', 'persona'],
        ];
        $servicio = function (int $dia, ?string $hora, string $tipo, string $titulo, ?int $proveedorId, array $extra = []) use ($programa, $inicio, $estado, $i, $costos) {
            $e = $estado();
            $fecha = $inicio->copy()->addDays($dia);
            [$costo, $moneda, $por] = $costos[$tipo] ?? [null, 'USD', 'grupo'];
            // Pagados: los servicios ya prestados de programas confirmados (salvo algunos, para ver vencidos).
            $pagado = $e === 'confirmado' && $fecha->lt(now()->startOfDay()) && ($dia + $i) % 6 !== 0;
            // Cotizaciones: se vende con costo estimado y sin proveedor (lo asigna Reservas cuando el cliente acepta).
            // Confirmados: proveedor y costo real; algunos tours salieron más caros que lo cotizado (alerta de margen).
            $esCotizacion = $programa->estado === 'cotizacion';
            $real = $costo !== null && in_array($tipo, ['tour', 'guia'], true) && ($dia + $i) % 5 === 0 ? round($costo * 1.15, 2) : $costo;
            $extra += ['costo_estimado' => $costo, 'costo' => $esCotizacion ? null : $real, 'moneda' => $moneda, 'costo_por' => $por];
            // En algunos confirmados el restaurante todavía no está decidido: queda "sin asignar" con su costo estimado.
            if ($esCotizacion || ($tipo === 'comida' && $i % 2 === 0)) {
                $proveedorId = null;
                $extra['costo'] = null;
                $e = 'pendiente';
                $pagado = false;
            }
            $nuevo = $programa->servicios()->create($extra + [
                'fecha' => $inicio->copy()->addDays($dia)->toDateString(),
                'hora' => $hora,
                'tipo' => $tipo,
                'titulo' => $titulo,
                'proveedor_id' => $proveedorId,
                'estado' => $e,
                'confirmacion' => $e === 'confirmado' ? 'CF-' . strtoupper(substr(md5($programa->id . $titulo . $dia), 0, 6)) : null,
                'visible_cliente' => true,
            ]);
            if ($pagado) {
                $nuevo->load('programa.paxs');
                $nuevo->pagos()->create([
                    'fecha' => $fecha->toDateString(), 'monto' => $nuevo->importe, 'moneda' => $nuevo->moneda, 'metodo' => 'transferencia',
                    'referencia' => 'OP-' . str_pad((string) ($programa->id * 10 + $dia), 6, '0', STR_PAD_LEFT),
                ]);
            }
        };

        $programa->dias()->create(['fecha' => $inicio->toDateString(), 'titulo' => 'Llegada y bienvenida',
            'descripcion' => 'Recepción en el aeropuerto y traslado al hotel. Tarde libre para aclimatarse a la altura.']);
        $servicio(0, '10:30', 'traslado', 'Traslado aeropuerto → hotel', $prov(0), ['punto_encuentro' => 'Sala de llegadas, cartel con el nombre del titular',
            'notas' => 'Vehículo privado. Llevar agua y hojas de coca.']);

        for ($d = 1; $d < $dias; $d++) {
            if ($machuPicchu && $d === 2) {
                $programa->dias()->create(['fecha' => $inicio->copy()->addDays($d)->toDateString(), 'titulo' => 'Machu Picchu',
                    'descripcion' => 'Tren panorámico a Aguas Calientes y visita guiada a la ciudadela inca.']);
                $servicio($d, '06:10', 'tren', 'Tren Ollantaytambo → Aguas Calientes', $prov(3), ['punto_encuentro' => 'Estación de Ollantaytambo']);
                $servicio($d, '10:00', 'entrada', 'Entrada a Machu Picchu (circuito 2)', $prov(4), ['visible_cliente' => true]);
                $servicio($d, '10:00', 'guia', 'Guía privado en Machu Picchu', $prov(1), ['notas' => 'Guía bilingüe ' . ($programa->lang === 'en' ? 'inglés' : 'español') . '.']);

                continue;
            }
            $programa->dias()->create(['fecha' => $inicio->copy()->addDays($d)->toDateString(), 'titulo' => 'Excursión del día ' . ($d + 1)]);
            $servicio($d, '08:30', 'tour', 'Excursión privada día ' . ($d + 1), $prov(1), ['punto_encuentro' => 'Lobby del hotel']);
            if ($d % 2 === 1) {
                $servicio($d, '13:00', 'comida', 'Almuerzo en restaurante local', $prov(2), ['notas' => 'Revisar dietas y alergias del grupo.']);
            }
        }

        $programa->dias()->create(['fecha' => $inicio->copy()->addDays($dias)->toDateString(), 'titulo' => 'Despedida']);
        $servicio($dias, '09:00', 'traslado', 'Traslado hotel → aeropuerto', $prov(0), ['punto_encuentro' => 'Lobby del hotel']);

        if ($confirmado) {
            foreach (['machu_picchu', 'datos_pax', 'tren', 'hoteles', 'itinerario_final', 'briefing'] as $k => $clave) {
                [$titulo, $diasAntes] = Tarea::PLANTILLAS[$clave];
                $limite = $inicio->copy()->subDays($diasAntes);
                // Las ya vencidas quedan hechas, salvo una de vez en cuando (para ver la alerta).
                $hecha = $limite->lt(now()) && ($i + $k) % 4 !== 0;
                $programa->tareas()->create([
                    'titulo' => $titulo,
                    'fecha_limite' => $limite->toDateString(),
                    'completada_at' => $hecha ? $limite->copy()->setTime(10, 0) : null,
                ]);
            }
        }
    }

    private function ensureProveedoresDemo(int $categoriaId): Collection
    {
        $filas = [
            ['categoria' => 'Transporte', 'nombre' => 'DEMO Transportes Andinos', 'ruc' => '20123456781', 'telefono' => '+51 1 6000001', 'correo' => 'transportes.demo@example.com'],
            ['categoria' => 'Guía', 'nombre' => 'DEMO Guías Cusco', 'ruc' => '20123456782', 'telefono' => '+51 84 6000002', 'correo' => 'guias.demo@example.com'],
            ['categoria' => 'Restaurante', 'nombre' => 'DEMO Restaurantes Altura', 'ruc' => '20123456783', 'telefono' => '+51 1 6000003', 'correo' => 'rest.demo@example.com'],
            ['categoria' => 'Tren y buses', 'nombre' => 'DEMO Buses Turísticos', 'ruc' => '20123456784', 'telefono' => '+51 1 6000004', 'correo' => 'buses.demo@example.com'],
            ['categoria' => 'Entradas', 'nombre' => 'DEMO Entradas Culturales', 'ruc' => '20123456785', 'telefono' => '+51 1 6000005', 'correo' => 'entradas.demo@example.com'],
            ['categoria' => 'Seguros', 'nombre' => 'DEMO Seguros Viaje', 'ruc' => '20123456786', 'telefono' => '+51 1 6000006', 'correo' => 'seguros.demo@example.com'],
            ['categoria' => 'Equipamiento', 'nombre' => 'DEMO Equipamiento Trek', 'ruc' => '20123456787', 'telefono' => '+51 1 6000007', 'correo' => 'trek.demo@example.com'],
            ['categoria' => 'Cruceros', 'nombre' => 'DEMO Cruceros Amazonas', 'ruc' => '20123456788', 'telefono' => '+51 65 6000008', 'correo' => 'amazon.demo@example.com'],
        ];

        foreach ($filas as $row) {
            $categoria = Categoria::query()->firstOrCreate(['nombre' => $row['categoria']])->id ?: $categoriaId;
            unset($row['categoria']);
            Proveedor::query()->updateOrCreate(
                ['nombre' => $row['nombre']],
                array_merge($row, ['categoria_id' => $categoria, 'direccion' => 'Lima, Perú', 'detalles' => 'Proveedor de demostración'])
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
                    'ruc' => '20'.str_pad((string) crc32($hd['nombre']), 7, '0', STR_PAD_LEFT),
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

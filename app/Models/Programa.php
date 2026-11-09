<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Programa extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    protected $table = 'programas';

    /** Estado comercial: clave => etiqueta. */
    public const ESTADOS = [
        'cotizacion' => 'Cotización',
        'confirmado' => 'Confirmado',
        'cancelado' => 'Cancelado',
    ];

    /** Reglas de cobro de los Términos y Condiciones (cláusula 8). */
    public const PORCENTAJE_DEPOSITO = 50;

    public const DIAS_SALDO_ANTES_DEL_VIAJE = 60;

    /** Plazo para el depósito desde que se confirma el itinerario (Términos, cláusula 8). */
    public const DIAS_PARA_DEPOSITO = 7;

    /** Soles por dólar si el programa no indica su tipo de cambio. */
    public const TIPO_CAMBIO_DEFECTO = 3.75;

    protected $casts = [
        'confirmado_at' => 'datetime',
        'itinerario_visto_at' => 'datetime',
        'llegada_at' => 'datetime',
        'salida_at' => 'datetime',
        'cerrado_at' => 'datetime',
        'plantilla' => 'boolean',
        'porcentaje_igv' => 'decimal:2',
        'porcentaje_ganancia' => 'decimal:2',
        'cantidad_pax' => 'integer',
    ];

    protected $fillable = [
        'nombre',
        'codigo',
        'estado',
        'plantilla',
        'cliente_id',
        'inicio',
        'fin',
        'lang',
        'moneda',
        'cantidad_pax',
        'precioAdulto',
        'precioChild',
        'porcentaje_ganancia',
        'porcentaje_igv',
        'tipo_cambio',
        'suplemento_individual',
        'habitaciones_individuales',
        'presentacion',
        'portada',
        'llegada_vuelo',
        'llegada_at',
        'salida_vuelo',
        'salida_at',
        'cerrado_at',
        'cerrado_por',
        'anio_id',
        'mes_id',
    ];

    public function simboloMoneda(): string
    {
        return Servicio::MONEDAS[$this->moneda ?: 'USD'] ?? '$';
    }

    protected function presentacion(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(set: fn (?string $html) => \App\Support\HtmlSeguro::limpiar($html));
    }

    protected static function booted(): void
    {
        // Una plantilla no es un viaje: no cuenta en listados, estadísticas, reservas ni cuentas.
        static::addGlobalScope('sin_plantillas', fn ($query) => $query->where($query->getModel()->qualifyColumn('plantilla'), false));

        // Enlace secreto del itinerario del cliente (la URL pública no usa el ID secuencial).
        static::creating(function (Programa $programa) {
            $programa->token ??= Str::random(32);
            // Código correlativo por año: AET-2026-0001 (si no se indicó uno).
            if (blank($programa->codigo)) {
                $programa->codigo = self::siguienteCodigo($programa->inicio ? Carbon::parse($programa->inicio)->year : null);
            }
        });

        // Año y mes se derivan siempre de la fecha de inicio (antes se elegían a mano y podían no coincidir).
        static::saving(function (Programa $programa) {
            // Fecha de confirmación: desde ahí corre el plazo del depósito.
            if ($programa->isDirty('estado')) {
                $programa->confirmado_at = $programa->estado === 'confirmado' ? ($programa->confirmado_at ?? now()) : null;
            }

            if ($programa->plantilla) {
                $programa->estado = 'cotizacion';
                $programa->anio_id = $programa->mes_id = null;

                return;
            }

            if (! $programa->inicio) {
                return;
            }
            $inicio = Carbon::parse($programa->inicio);
            $programa->anio_id = Anio::firstOrCreate(['anio' => $inicio->year])->id;
            $programa->mes_id = Mes::where('numero', $inicio->month)->value('id');
        });
    }

    public static function siguienteCodigo(?int $anio = null): string
    {
        $prefijo = 'AET-' . ($anio ?? now()->year) . '-';
        $ultimo = static::query()->where('codigo', 'like', $prefijo . '%')->orderByDesc('codigo')->value('codigo');
        $numero = $ultimo ? (int) substr($ultimo, strlen($prefijo)) + 1 : 1;

        return $prefijo . str_pad((string) $numero, 4, '0', STR_PAD_LEFT);
    }

    /** Correo de contacto del programa: el del cliente titular. */
    public function getEmailAttribute(): ?string
    {
        return $this->cliente?->email;
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class)->orderBy('fecha');
    }

    public function agentes()
    {
        return $this->belongsToMany(Agente::class, 'agente_programa', 'programa_id', 'agente_id');
    }

public function paxs()
    {
        // chaperone(): cada pax conoce su programa sin otra consulta (la edad se calcula a la fecha de inicio).
        return $this->hasMany(Pax::class)->chaperone();
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function notificaciones()
    {
        return $this->hasMany(Notificacion::class)->latest();
    }

    /** Pasajeros que pagan (5 años o más a la fecha del viaje, o la cantidad de pasajeros del grupo si no hay lista detallada). */
    public function pagantes(): int
    {
        $enLista = $this->paxs->filter(fn (Pax $pax) => $pax->edad === null || $pax->edad >= self::EDAD_NINO_MIN)->count();
        if ($enLista > 0) {
            return $enLista;
        }

        return max(1, (int) ($this->cantidad_pax ?: 1));
    }

    /** Total de personas en el grupo (desde la lista de paxs o la cantidad de pasajeros indicada en datos del grupo). */
    public function totalPaxs(): int
    {
        $enLista = $this->paxs->count();
        if ($enLista > 0) {
            return $enLista;
        }

        return max(1, (int) ($this->cantidad_pax ?: 1));
    }

    public function tipoCambio(): float
    {
        return (float) ($this->tipo_cambio ?: self::TIPO_CAMBIO_DEFECTO);
    }

    /**
     * Costo real según los servicios con costo (en USD, convirtiendo soles con el tipo de cambio) y margen
     * frente al precio de venta. Requiere servicios y paxs (se cargan si faltan).
     *
     * @return array{costo: float, costoEstimado: float, venta: float, margen: float, margenPct: float, margenCotizado: float, margenCotizadoPct: float, sinCosto: int, sinProveedor: int, aumentos: \Illuminate\Support\Collection, pagantes: int, costoPorPagante: float, porPagar: float}
     */
    /** Habitaciones individuales según el alojamiento: la noche con más, para comparar con el suplemento. */
    public function individualesSegunAlojamiento(): int
    {
        $porNoche = [];
        foreach ($this->habitacionesFechas()->with('habitacion')->get() as $estadia) {
            if ((int) $estadia->habitacion?->capacidad !== 1 || ! $estadia->noches) {
                continue;
            }
            for ($dia = \Carbon\Carbon::parse($estadia->fecha_ingreso), $n = 0; $n < $estadia->noches; $n++, $dia->addDay()) {
                $porNoche[$dia->toDateString()] = ($porNoche[$dia->toDateString()] ?? 0) + $estadia->cantidad;
            }
        }

        return $porNoche ? max($porNoche) : 0;
    }

    public function costeo(): array
    {
        $this->loadMissing(['servicios', 'paxs']);
        $activos = $this->servicios->where('estado', '!=', 'cancelado');
        $activos->each(fn (Servicio $s) => $s->setRelation('programa', $this));

        $usd = fn (Servicio $s, float $monto) => $s->moneda === 'PEN' ? $monto / $this->tipoCambio() : $monto;
        // Costo previsto: el real cuando el proveedor ya lo dio; si no, el estimado con el que se cotizó.
        $costo = $activos->sum(fn (Servicio $s) => $s->importe_previsto_usd);
        $costoEstimado = $activos->sum(fn (Servicio $s) => $usd($s, $s->importe_estimado ?: ($s->costo !== null ? $s->importe : 0)));
        $venta = $this->presupuesto()['total'];
        $pagantes = $this->pagantes();

        return [
            'costo' => $costo,
            'costoEstimado' => $costoEstimado,
            'venta' => $venta,
            'margen' => $venta - $costo,
            'margenPct' => $venta > 0 ? ($venta - $costo) / $venta * 100 : 0,
            'margenCotizado' => $venta - $costoEstimado,
            'margenCotizadoPct' => $venta > 0 ? ($venta - $costoEstimado) / $venta * 100 : 0,
            'sinCosto' => $activos->filter(fn (Servicio $s) => $s->costo === null && $s->costo_estimado === null)->count(),
            'sinProveedor' => $activos->where('estado', 'sin_asignar')->count(),
            // Servicios cuyo costo real superó lo cotizado (alerta de margen).
            'aumentos' => $activos->filter(fn (Servicio $s) => ($s->desvio_costo ?? 0) > 0.009)->values(),
            'pagantes' => $pagantes,
            'costoPorPagante' => $pagantes ? $costo / $pagantes : 0,
            'porPagar' => $this->estado === 'confirmado'
                ? $activos->filter(fn (Servicio $s) => $s->costo > 0 && $s->estado === 'confirmado' && ! $s->pagado_at)
                    ->sum(fn (Servicio $s) => $usd($s, $s->saldo_proveedor))
                : 0.0,
        ];
    }

    /** Proveedores contratados: los de sus servicios (sin repetir). */
    public function proveedores()
    {
        return $this->belongsToMany(Proveedor::class, 'servicios')->distinct();
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class)->orderBy('fecha')->orderByRaw('hora IS NULL, hora')->orderBy('id');
    }

    /** Las pantallas sí abren plantillas por su enlace. */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withoutGlobalScope('sin_plantillas')->where($field ?? $this->getRouteKeyName(), $value)->first();
    }

    public function scopePlantillas($query)
    {
        return $query->withoutGlobalScope('sin_plantillas')->where('plantilla', true);
    }

    /** Lo que falta para dar por terminado el viaje. Vacío = se puede cerrar. */
    public function pendientesParaCerrar(): array
    {
        $this->loadMissing(['paxs', 'pagos']);
        $pendientes = [];

        if ($this->estado !== 'confirmado') {
            $pendientes[] = 'el programa no está confirmado.';
        } elseif (! $this->fin || Carbon::parse($this->fin)->startOfDay()->gte(Carbon::today())) {
            $pendientes[] = 'el viaje aún no termina.';
        }
        if ($this->cobranza()['saldo'] > 0.009) {
            $pendientes[] = 'el cliente tiene saldo por pagar.';
        }
        if ($this->servicios()->abiertos()->exists()) {
            $pendientes[] = 'hay servicios sin confirmar ni cancelar.';
        }
        if ($this->servicios()->porPagar()->exists()) {
            $pendientes[] = 'hay servicios sin pagar al proveedor.';
        }

        return $pendientes;
    }

    public function aceptaciones()
    {
        return $this->hasMany(Aceptacion::class)->latest('id');
    }

    /** Lo que el cliente ve y acepta. Si algo de esto cambia, su aceptación deja de valer para la propuesta nueva. */
    public function fotoDePropuesta(): array
    {
        $this->loadMissing(['paxs']);
        $p = $this->presupuesto();

        return [
            'nombre' => $this->nombre,
            'inicio' => $this->inicio ? (string) $this->inicio : null,
            'fin' => $this->fin ? (string) $this->fin : null,
            'adultos' => $p['adultos'], 'ninos' => $p['ninos'], 'infantes' => $p['infantes'],
            'total' => round((float) $p['total'], 2),
            'servicios' => $this->servicios()->where('visible_cliente', true)->where('estado', '!=', 'cancelado')->orderBy('fecha')->orderBy('hora')->pluck('titulo')->all(),
            'alojamiento' => $this->habitacionesFechas()->with('habitacion.hotel')->orderBy('fecha_ingreso')->orderBy('id')->get()
                ->map(fn (HabitacionFecha $e) => trim(($e->habitacion?->hotel?->nombre ?? '') . ' · ' . $e->etiqueta . ' · ' . $e->fecha_ingreso))->all(),
        ];
    }

    public static function huellaDe(array $propuesta): string
    {
        return sha1(json_encode($propuesta));
    }

    /** Última aceptación, solo si la propuesta sigue igual. */
    public function aceptacionVigente(): ?Aceptacion
    {
        $ultima = $this->aceptaciones()->first();

        return $ultima && $ultima->huella === self::huellaDe($this->fotoDePropuesta()) ? $ultima : null;
    }

    public function aceptable(): bool
    {
        return $this->estado === 'cotizacion' && ! $this->plantilla && (float) $this->presupuesto()['total'] > 0;
    }

    public function dias()
    {
        return $this->hasMany(ProgramaDia::class)->orderBy('fecha');
    }

    public function tareas()
    {
        return $this->hasMany(Tarea::class)->orderByRaw('completada_at IS NOT NULL')->orderByRaw('fecha_limite IS NULL, fecha_limite')->orderBy('id');
    }

    public function habitacionesFechas()
    {
        return $this->hasMany(HabitacionFecha::class, 'programa_id');
    }

    /**
     * Alojamiento agrupado por hotel, en orden de llegada (lo que aún no tiene fechas, al final).
     *
     * @return \Illuminate\Support\Collection<int, array{hotel: Hotel, estadias: \Illuminate\Support\Collection}>
     */
    public function alojamiento(): \Illuminate\Support\Collection
    {
        $this->loadMissing('habitacionesFechas.habitacion.hotel');

        return $this->habitacionesFechas
            ->filter(fn (HabitacionFecha $e) => $e->habitacion?->hotel)
            ->sortBy(fn (HabitacionFecha $e) => [$e->fecha_ingreso === null, (string) $e->fecha_ingreso, $e->id])
            ->groupBy(fn (HabitacionFecha $e) => $e->habitacion->hotel_id)
            ->map(fn ($estadias) => ['hotel' => $estadias->first()->habitacion->hotel, 'estadias' => $estadias->values()]);
    }

    public function anio()
    {
        return $this->belongsTo(Anio::class, 'anio_id');
    }

    public function mes()
    {
        return $this->belongsTo(Mes::class, 'mes_id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'programa_id')->latest()->orderByDesc('id');
    }

    /** Indica si el grupo tiene algún dato de vuelo de llegada o salida registrado. */
    public function tieneVuelos(): bool
    {
        return filled($this->llegada_vuelo) || filled($this->salida_vuelo) || $this->llegada_at !== null || $this->salida_at !== null;
    }

    /** Resumen de tareas operativas del grupo. */
    public function tareasResumen(): array
    {
        $this->loadMissing('tareas');
        $pendientes = $this->tareas->whereNull('completada_at');
        $vencidas = $pendientes->filter(fn ($t) => $t->fecha_limite && Carbon::parse($t->fecha_limite)->isPast())->count();

        return [
            'total' => $this->tareas->count(),
            'pendientes' => $pendientes->count(),
            'vencidas' => $vencidas,
            'completadas' => $this->tareas->whereNotNull('completada_at')->count(),
        ];
    }

    /** Edad mínima para tarifa de adulto y rango de tarifa infantil (los menores de 5 no pagan). */
    public const EDAD_ADULTO = 12;

    public const EDAD_NINO_MIN = 5;

    /**
     * Presupuesto según los pasajeros cargados: adultos (12+), niños (5-11) e infantes (<5, gratis),
     * más el % de ganancia. Requiere la relación paxs (se carga si falta).
     *
     * @return array{ventaAdulto: float, ventaNino: float, adultos: int, ninos: int, infantes: int, subtotalAdultos: float, subtotalNinos: float, subtotal: float, pctGanancia: float, ganancia: float, suplementoIndividual: float, habitacionesIndividuales: int, suplemento: float, total: float}
     */
    public function presupuesto(): array
    {
        $paxs = $this->paxs;
        $adultos = $paxs->where('edad', '>=', self::EDAD_ADULTO)->count();
        $ninos = $paxs->whereBetween('edad', [self::EDAD_NINO_MIN, self::EDAD_ADULTO - 1])->count();
        $infantes = $paxs->where('edad', '<', self::EDAD_NINO_MIN)->count();

        // Si aún no se han ingresado pasajeros con nombre en la pestaña Pasajeros,
        // usamos la cantidad de pasajeros del grupo como base para cotizar.
        if ($paxs->isEmpty()) {
            $adultos = max(1, (int) ($this->cantidad_pax ?: 1));
        }

        $subtotalAdultos = $adultos * (float) $this->precioAdulto;
        $subtotalNinos = $ninos * (float) $this->precioChild;
        $subtotal = $subtotalAdultos + $subtotalNinos;
        $pct = (float) ($this->porcentaje_ganancia ?? 0);
        $ganancia = $subtotal * $pct / 100;
        $factor = 1 + $pct / 100;
        // Suplemento por habitación individual: precio de venta (ya incluye la ganancia).
        $suplemento = (float) $this->suplemento_individual * (int) $this->habitaciones_individuales;

        return [
            // Precio de venta por persona (con la ganancia incluida): lo que ve el cliente.
            'ventaAdulto' => (float) $this->precioAdulto * $factor,
            'ventaNino' => (float) $this->precioChild * $factor,
            'adultos' => $adultos,
            'ninos' => $ninos,
            'infantes' => $infantes,
            'subtotalAdultos' => $subtotalAdultos,
            'subtotalNinos' => $subtotalNinos,
            'subtotal' => $subtotal,
            'pctGanancia' => $pct,
            'pctIgv' => (float) ($this->porcentaje_igv ?? 18.0),
            'ganancia' => $ganancia,
            'suplementoIndividual' => (float) $this->suplemento_individual,
            'habitacionesIndividuales' => (int) $this->habitaciones_individuales,
            'suplemento' => $suplemento,
            'total' => $subtotal + $ganancia + $suplemento,
        ];
    }

    /**
     * Situación de cobro según los pagos registrados y las reglas de los Términos:
     * depósito del 50 % y saldo hasta 60 días antes del inicio. Requiere paxs y pagos (se cargan si faltan).
     *
     * @return array{total: float, pagado: float, saldo: float, porcentaje: int, deposito: float, depositoCubierto: bool, vencimientoSaldo: ?Carbon, saldoVencido: bool, estado: string}
     */
    public function cobranza(): array
    {
        $total = $this->presupuesto()['total'];
        $pagado = (float) $this->pagos->sum('monto');
        $saldo = max(0, $total - $pagado);
        $vencimiento = $this->inicio ? Carbon::parse($this->inicio)->subDays(self::DIAS_SALDO_ANTES_DEL_VIAJE)->startOfDay() : null;
        $deposito = $total * self::PORCENTAJE_DEPOSITO / 100;
        $vencimientoDeposito = $this->confirmado_at ? Carbon::parse($this->confirmado_at)->addDays(self::DIAS_PARA_DEPOSITO)->startOfDay() : null;

        $estado = match (true) {
            $total <= 0 => 'sin_tarifa',
            $pagado >= $total - 0.009 => 'pagado',
            $pagado >= $deposito - 0.009 => 'deposito',
            $pagado > 0 => 'parcial',
            default => 'pendiente',
        };

        return [
            'total' => $total,
            'pagado' => $pagado,
            'saldo' => $saldo,
            'porcentaje' => $total > 0 ? (int) min(100, round($pagado / $total * 100)) : 0,
            'deposito' => $deposito,
            'depositoCubierto' => $total > 0 && $pagado >= $deposito - 0.009,
            'vencimientoSaldo' => $vencimiento,
            'vencimientoDeposito' => $vencimientoDeposito,
            'depositoVencido' => $total > 0 && $pagado < $deposito - 0.009 && $vencimientoDeposito !== null && Carbon::today()->gt($vencimientoDeposito) && $this->estado === 'confirmado',
            'saldoVencido' => $saldo > 0 && $vencimiento !== null && Carbon::today()->gt($vencimiento) && $this->estado !== 'cancelado',
            'estado' => $estado,
        ];
    }

    /**
     * Fase del viaje respecto de hoy (distinta del estado comercial $this->estado): 'sin_fechas' | 'proximo' | 'en_curso' | 'finalizado'.
     */
    public function faseViaje(): string
    {
        if (! $this->inicio || ! $this->fin) {
            return 'sin_fechas';
        }

        $hoy = Carbon::today();
        $inicio = Carbon::parse($this->inicio)->startOfDay();
        $fin = Carbon::parse($this->fin)->startOfDay();

        return match (true) {
            $hoy->lt($inicio) => 'proximo',
            $hoy->gt($fin) => 'finalizado',
            default => 'en_curso',
        };
    }

    /** Días que faltan para el inicio (null si no hay fecha o ya empezó). */
    public function diasParaInicio(): ?int
    {
        if (! $this->inicio) {
            return null;
        }
        $dias = (int) Carbon::today()->diffInDays(Carbon::parse($this->inicio)->startOfDay(), false);

        return $dias >= 0 ? $dias : null;
    }

    /**
     * Itinerario armado día a día desde inicio hasta fin: título y texto del día, servicios y hotel de esa noche.
     * Los servicios con fecha fuera del rango quedan en 'fuera' para no perderlos.
     *
     * @param  bool  $soloCliente  Solo servicios visibles para el cliente y no cancelados.
     * @return array{dias: \Illuminate\Support\Collection, fuera: \Illuminate\Support\Collection}
     */
    public function itinerario(bool $soloCliente = false): array
    {
        $this->loadMissing(['dias', 'servicios.proveedor', 'habitacionesFechas.habitacion.hotel']);

        $servicios = $soloCliente
            ? $this->servicios->filter(fn (Servicio $s) => $s->visible_cliente && $s->estado !== 'cancelado' && ! in_array($s->tipo, Servicio::SOLO_INTERNOS, true))
            : $this->servicios;
        $porFecha = $servicios->groupBy(fn (Servicio $s) => $s->fecha->toDateString());
        $diasPorFecha = $this->dias->keyBy(fn (ProgramaDia $d) => $d->fecha->toDateString());

        if (! $this->inicio || ! $this->fin || Carbon::parse($this->fin)->lt(Carbon::parse($this->inicio))) {
            return ['dias' => collect(), 'fuera' => $servicios->values()];
        }

        $dias = collect();
        $fecha = Carbon::parse($this->inicio)->startOfDay();
        $fin = Carbon::parse($this->fin)->startOfDay();
        for ($n = 1; $fecha->lte($fin); $n++, $fecha->addDay()) {
            $clave = $fecha->toDateString();
            // Noche del día: las estadías cuyo ingreso es ese día o antes y la salida es posterior (un grupo puede dividirse en dos hoteles).
            $hoteles = $this->habitacionesFechas
                ->filter(fn ($hf) => $hf->fecha_ingreso && $hf->fecha_salida && $hf->fecha_ingreso <= $clave && $hf->fecha_salida > $clave)
                ->map(fn ($hf) => $hf->habitacion?->hotel?->nombre)->filter()->unique();
            $registroDia = $diasPorFecha->get($clave);
            $dias->push([
                'numero' => $n,
                'fecha' => $fecha->copy(),
                'clave' => $clave,
                'dia' => $registroDia,
                'es_libre' => (bool) ($registroDia?->es_libre),
                'servicios' => $porFecha->get($clave, collect())->values(),
                'hotel' => $hoteles->implode(' / ') ?: null,
            ]);
        }

        $inicio = Carbon::parse($this->inicio)->startOfDay();
        $fuera = $servicios->filter(fn (Servicio $s) => $s->fecha->lt($inicio) || $s->fecha->gt($fin))->values();

        return ['dias' => $dias, 'fuera' => $fuera];
    }

    /**
     * Avance operativo: servicios por estado y tareas pendientes/vencidas. Requiere servicios y tareas (se cargan si faltan).
     *
     * @return array{servicios: int, confirmados: int, abiertos: int, tareasPendientes: int, tareasVencidas: int}
     */
    public function operacion(): array
    {
        $this->loadMissing(['servicios', 'tareas']);
        $activos = $this->servicios->where('estado', '!=', 'cancelado');
        $pendientes = $this->tareas->whereNull('completada_at');

        return [
            'servicios' => $activos->count(),
            'confirmados' => $activos->where('estado', 'confirmado')->count(),
            'abiertos' => $activos->whereIn('estado', Servicio::ABIERTOS)->count(),
            'tareasPendientes' => $pendientes->count(),
            'tareasVencidas' => $pendientes->filter(fn (Tarea $t) => $t->situacion() === 'vencida')->count(),
        ];
    }

    public function duracionDias(): ?int
    {
        if (! $this->inicio || ! $this->fin) {
            return null;
        }

        return (int) Carbon::parse($this->inicio)->diffInDays(Carbon::parse($this->fin)) + 1;
    }
}

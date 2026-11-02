<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Servicio operado en un día del programa (traslado, guía, tren, entrada...), con su estado de reserva. */
class Servicio extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    /** Tipo => [etiqueta ES, etiqueta EN, icono Font Awesome 5 (panel), icono Font Awesome 4 (itinerario del cliente)]. */
    public const TIPOS = [
        'traslado' => ['Traslado', 'Transfer', 'fa-shuttle-van', 'fa-car'],
        'tour' => ['Tour / excursión', 'Tour', 'fa-map-signs', 'fa-map-signs'],
        'guia' => ['Guía', 'Guide', 'fa-user-tie', 'fa-user'],
        'tren' => ['Tren', 'Train', 'fa-train', 'fa-train'],
        'vuelo' => ['Vuelo', 'Flight', 'fa-plane', 'fa-plane'],
        'entrada' => ['Entrada / ticket', 'Entrance ticket', 'fa-ticket-alt', 'fa-ticket'],
        'comida' => ['Comida', 'Meal', 'fa-utensils', 'fa-cutlery'],
        'actividad' => ['Actividad', 'Activity', 'fa-hiking', 'fa-star'],
        'alojamiento' => ['Alojamiento (costo)', 'Accommodation', 'fa-hotel', 'fa-bed'],
        'otro' => ['Otro', 'Other', 'fa-concierge-bell', 'fa-check'],
    ];

    /** Tipos internos: solo para costear y pagar; el cliente ve los hoteles en la pestaña Alojamiento. */
    public const SOLO_INTERNOS = ['alojamiento'];

    public const MONEDAS = ['USD' => 'US$', 'PEN' => 'S/'];

    /** El costo se cobra una vez por el grupo o por cada pasajero que paga (los menores de 5 no pagan). */
    public const COSTO_POR = ['grupo' => 'por grupo', 'persona' => 'por persona'];

    /**
     * Estado de la reserva con el proveedor. 'sin_asignar' es automático: servicio vendido sin proveedor elegido
     * (al asignarle uno pasa a 'pendiente' = por reservar). Solicitar o confirmar exige proveedor.
     */
    public const ESTADOS = [
        'sin_asignar' => 'Sin asignar',
        'pendiente' => 'Por reservar',
        'solicitado' => 'Solicitado',
        'confirmado' => 'Confirmado',
        'cancelado' => 'Cancelado',
    ];

    /** Estados que todavía requieren gestión. */
    public const ABIERTOS = ['sin_asignar', 'pendiente', 'solicitado'];

    /** Estados que se eligen a mano ('sin_asignar' lo pone el sistema según haya o no proveedor). */
    public const ESTADOS_MANUALES = ['pendiente', 'solicitado', 'confirmado', 'cancelado'];

    /** Estados que requieren proveedor asignado. */
    public const REQUIEREN_PROVEEDOR = ['solicitado', 'confirmado'];

    protected $fillable = [
        'programa_id', 'fecha', 'hora', 'tipo', 'titulo', 'descripcion', 'proveedor_id',
        'punto_encuentro', 'estado', 'confirmacion', 'notas', 'visible_cliente',
        'costo', 'costo_estimado', 'moneda', 'costo_por', 'pagado_at', 'pago_solicitado_at', 'pago_solicitado_por',
    ];

    protected $casts = [
        'fecha' => 'date',
        'visible_cliente' => 'boolean',
        'costo' => 'decimal:2',
        'costo_estimado' => 'decimal:2',
        'pagado_at' => 'date',
        'pago_solicitado_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        // Sin proveedor no hay nada que reservar; con proveedor, deja de estar "sin asignar".
        static::saving(function (Servicio $s) {
            if (! $s->proveedor_id && $s->estado === 'pendiente') {
                $s->estado = 'sin_asignar';
            } elseif ($s->proveedor_id && $s->estado === 'sin_asignar') {
                $s->estado = 'pendiente';
            }
        });

        // Historial: estado inicial y cada cambio de estado o de n.º de confirmación (con el usuario que lo hizo).
        static::created(fn (Servicio $s) => $s->registrarEvento('estado', null, $s->estado, $s->confirmacion ? 'Conf. ' . $s->confirmacion : null));

        static::updated(function (Servicio $s) {
            // Primero: recalcularPago() guarda el modelo y se perderían los valores anteriores que usan los avisos.
            \App\Support\ReglasDeAvisos::servicioCambiado($s);

            if ($s->wasChanged('estado')) {
                $s->registrarEvento('estado', $s->getOriginal('estado'), $s->estado, $s->confirmacion ? 'Conf. ' . $s->confirmacion : null);
            }
            if ($s->wasChanged('proveedor_id') && $s->proveedor_id) {
                $s->registrarEvento('nota', null, null, 'Proveedor asignado: ' . $s->proveedor()->value('nombre')
                    . ($s->costo !== null ? ' · costo ' . self::formatoMonto((float) $s->costo, $s->moneda) : ''));
            }
            if (! $s->wasChanged('estado') && $s->wasChanged('confirmacion') && $s->confirmacion) {
                $s->registrarEvento('nota', null, null, 'N.º de confirmación: ' . $s->confirmacion);
            }
            if ($s->wasChanged(['costo', 'costo_por', 'moneda'])) {
                $s->recalcularPago();
            }
        });
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function pagos()
    {
        return $this->hasMany(ProveedorPago::class)->orderBy('fecha')->orderBy('id');
    }

    public function eventos()
    {
        return $this->hasMany(ServicioEvento::class)->latest()->orderByDesc('id');
    }

    public function registrarEvento(string $tipo, ?string $de, ?string $a, ?string $detalle = null): void
    {
        $this->eventos()->create([
            'tipo' => $tipo, 'de' => $de, 'a' => $a, 'detalle' => $detalle ? mb_substr($detalle, 0, 255) : null,
            'user_id' => auth()->id(),
        ]);
    }

    /** Total pagado al proveedor (en la moneda del servicio). */
    public function getPagadoProveedorAttribute(): float
    {
        return (float) ($this->relationLoaded('pagos') ? $this->pagos->sum('monto') : $this->pagos()->sum('monto'));
    }

    public function getSaldoProveedorAttribute(): float
    {
        return max(0, round($this->importe - $this->pagado_proveedor, 2));
    }

    /** 'sin_costo' | 'pendiente' | 'parcial' | 'pagado'. */
    public function getSituacionPagoAttribute(): string
    {
        if ($this->costo === null || (float) $this->costo <= 0) {
            return 'sin_costo';
        }
        $pagado = $this->pagado_proveedor;

        return match (true) {
            $pagado <= 0 => 'pendiente',
            $pagado + 0.009 < $this->importe => 'parcial',
            default => 'pagado',
        };
    }

    /** pagado_at = fecha del pago que completó el importe (o null si queda saldo). */
    public function recalcularPago(): void
    {
        $this->unsetRelation('pagos');
        $pagado = $this->pagado_proveedor;
        $completo = $this->costo !== null && (float) $this->costo > 0 && $pagado + 0.009 >= $this->importe;
        $this->pagado_at = $completo ? $this->pagos()->max('fecha') : null;
        $this->saveQuietly();
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /** Hora en formato HH:MM (la columna TIME devuelve HH:MM:SS). */
    public function getHoraCortaAttribute(): ?string
    {
        return $this->hora ? substr($this->hora, 0, 5) : null;
    }

    public function getTipoEtiquetaAttribute(): string
    {
        return self::TIPOS[$this->tipo][0] ?? $this->tipo;
    }

    public function getIconoAttribute(): string
    {
        return self::TIPOS[$this->tipo][2] ?? 'fa-concierge-bell';
    }

    /**
     * Lo que se le debe al proveedor, en la moneda del servicio (costo × pasajeros que pagan si es por persona).
     * Requiere programa.paxs (se cargan si faltan).
     */
    public function getImporteAttribute(): float
    {
        if ($this->costo === null) {
            return 0.0;
        }

        return (float) $this->costo * ($this->costo_por === 'persona' ? $this->programa->pagantes() : 1);
    }

    /** Costo estimado total (con el que se cotizó), en la moneda del servicio. */
    public function getImporteEstimadoAttribute(): float
    {
        if ($this->costo_estimado === null) {
            return 0.0;
        }

        return (float) $this->costo_estimado * ($this->costo_por === 'persona' ? $this->programa->pagantes() : 1);
    }

    /** Costo previsto en USD: el real si el proveedor ya lo dio; si no, el estimado. */
    public function getImportePrevistoUsdAttribute(): float
    {
        $monto = $this->costo !== null ? $this->importe : $this->importe_estimado;

        return $this->moneda === 'PEN' ? $monto / $this->programa->tipoCambio() : $monto;
    }

    /** Diferencia real − estimado (en la moneda del servicio); null si falta alguno de los dos. */
    public function getDesvioCostoAttribute(): ?float
    {
        if ($this->costo === null || $this->costo_estimado === null) {
            return null;
        }

        return round($this->importe - $this->importe_estimado, 2);
    }

    /** Importe convertido a dólares con el tipo de cambio del programa. */
    public function getImporteUsdAttribute(): float
    {
        return $this->moneda === 'PEN' ? $this->importe / $this->programa->tipoCambio() : $this->importe;
    }

    public static function formatoMonto(float $monto, string $moneda = 'USD'): string
    {
        return (self::MONEDAS[$moneda] ?? '$') . ' ' . number_format($monto, 2);
    }

    /**
     * Deudas con proveedores: servicios con costo, confirmados con el proveedor, de programas confirmados.
     * (Una cotización o un servicio sin reservar todavía no genera deuda.)
     */
    public function scopeDeudas($query)
    {
        return $query->whereNotNull('costo')->where('costo', '>', 0)->where('estado', 'confirmado')
            ->whereHas('programa', fn ($q) => $q->where('estado', 'confirmado'));
    }

    /** Deudas aún no pagadas. */
    public function scopePorPagar($query)
    {
        return $query->deudas()->whereNull('pagado_at');
    }

    /** Pagos que Reservas pidió a Contabilidad y aún no se completan. */
    public function scopeSolicitudesPago($query)
    {
        return $query->whereNotNull('pago_solicitado_at')->whereNull('pagado_at')->where('estado', '!=', 'cancelado');
    }

    public function scopeAbiertos($query)
    {
        return $query->whereIn('estado', self::ABIERTOS);
    }

    /** Servicios vigentes de programas confirmados: una cotización todavía no es operación. */
    public function scopeOperables($query)
    {
        return $query->where('estado', '!=', 'cancelado')
            ->whereHas('programa', fn ($q) => $q->where('estado', 'confirmado'));
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TarifarioServicio extends Model
{
    protected $table = 'tarifario_servicios';

    public const CATEGORIAS = [
        'traslado'     => 'Traslados',
        'tour'         => 'Tours y Experiencias',
        'trek'         => 'Treks y Caminatas',
        'boleto'       => 'Boletos y Entradas Oficiales',
        'alimentacion' => 'Almuerzos y Picnics',
        'viatico'      => 'Viáticos Guía / Conductor',
        'propina'      => 'Cuadro de Propinas',
        'componente'   => 'Componentes y Logística',
        'otro'         => 'Otros Servicios',
    ];

    public const TIPOS_CALCULO = [
        'escala_pax'  => 'Escalonado según cantidad de pasajeros (1 a 20)',
        'por_persona' => 'Fijo por persona (Adulto / Niño)',
        'por_grupo'   => 'Fijo por grupo (se prorratea entre pasajeros)',
    ];

    protected $fillable = [
        'codigo',
        'categoria',
        'nombre',
        'descripcion',
        'lugares_visita',
        'duracion_horas',
        'tipo_calculo',
        'precio_adulto',
        'precio_nino',
        'costo_grupo',
        'comision_pct',
        'aplica_igv_comision',
        'moneda',
        'precios_escalonados',
        'desglose',
        'notas',
        'activo',
    ];

    protected $casts = [
        'duracion_horas'      => 'decimal:1',
        'precio_adulto'       => 'decimal:2',
        'precio_nino'         => 'decimal:2',
        'costo_grupo'         => 'decimal:2',
        'comision_pct'        => 'decimal:2',
        'aplica_igv_comision' => 'boolean',
        'precios_escalonados' => 'array',
        'desglose'            => 'array',
        'activo'              => 'boolean',
    ];

    public function scopeActivo(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function scopeCategoria(Builder $query, string $categoria): Builder
    {
        return $query->where('categoria', $categoria);
    }

    /**
     * Calcula el costo neto unitario (por persona) para un grupo de N pasajeros.
     */
    public function calcularCosto(int $pax = 1, bool $esFeriado = false, ?string $tipoPax = 'adulto'): float
    {
        $pax = max(1, $pax);

        if ($this->tipo_calculo === 'por_persona') {
            if ($tipoPax === 'nino' && $this->precio_nino !== null) {
                return (float) $this->precio_nino;
            }
            return (float) ($this->precio_adulto ?? 0.0);
        }

        if ($this->tipo_calculo === 'por_grupo') {
            return round(((float) ($this->costo_grupo ?? 0.0)) / $pax, 2);
        }

        // Tipo: escala_pax (1 a 20 pasajeros)
        $tabla = $this->precios_escalonados;
        if (! is_array($tabla)) {
            return (float) ($this->precio_adulto ?? 0.0);
        }

        if ($tipoPax === 'nino') {
            if ($esFeriado && isset($tabla['feriado_nino'])) {
                $seccion = $tabla['feriado_nino'];
            } elseif (isset($tabla['nino'])) {
                $seccion = $tabla['nino'];
            } else {
                $seccion = ($esFeriado && isset($tabla['feriado'])) ? $tabla['feriado'] : ($tabla['normal'] ?? $tabla);
            }
        } else {
            $seccion = ($esFeriado && isset($tabla['feriado'])) ? $tabla['feriado'] : ($tabla['normal'] ?? $tabla);
        }

        $paxKey = (string) min(20, $pax);

        if (isset($seccion[$paxKey])) {
            return (float) $seccion[$paxKey];
        }

        // Fallback a la clave más cercana o última
        if (! empty($seccion)) {
            $valores = array_values($seccion);
            return (float) end($valores);
        }

        return (float) ($this->precio_adulto ?? 0.0);
    }

    /**
     * Costo neto por pasajero para un grupo mixto: los precios fijos por persona se ponderan entre adultos y niños;
     * los escalonados y grupales se calculan con el total de pasajeros (como en la búsqueda del modal).
     */
    public function costoPorPax(int $adultos, int $ninos = 0, bool $esFeriado = false): float
    {
        $adultos = max(1, $adultos);
        $ninos = max(0, $ninos);
        $pax = $adultos + $ninos;

        if ($this->tipo_calculo === 'por_persona' && $this->precio_nino > 0) {
            $total = ($adultos * (float) ($this->precio_adulto ?? 0)) + ($ninos * (float) $this->precio_nino);

            return round($total / $pax, 2);
        }

        return round($this->calcularCosto($pax, $esFeriado), 2);
    }

    /**
     * Devuelve el desglose financiero completo: Costo Neto, Comisión Agencia e IGV al Fee.
     */
    public function calcularFinanciero(int $pax = 1, bool $esFeriado = false, ?string $tipoPax = 'adulto'): array
    {
        $costoNeto = $this->calcularCosto($pax, $esFeriado, $tipoPax);
        $comisionPct = (float) ($this->comision_pct ?? 11.0);
        $comisionMonto = $comisionPct > 0 ? ($costoNeto * ($comisionPct / 100)) : 0.0;
        $igvMonto = ($this->aplica_igv_comision && $comisionMonto > 0) ? ($comisionMonto * 0.18) : 0.0;
        $totalVenta = $costoNeto + $comisionMonto + $igvMonto;

        return [
            'codigo'              => $this->codigo,
            'nombre'              => $this->nombre,
            'categoria'           => $this->categoria,
            'moneda'              => $this->moneda,
            'pax'                 => $pax,
            'es_feriado'          => $esFeriado,
            'tipo_pax'            => $tipoPax,
            'costo_neto'          => round($costoNeto, 2),
            'costo_unitario'      => round($costoNeto, 2),
            'comision_pct'        => $comisionPct,
            'comision_monto'      => round($comisionMonto, 2),
            'igv_monto'           => round($igvMonto, 2),
            'total_venta'         => round($totalVenta, 2),
            'precio_venta_pax'    => round($totalVenta, 2),
            'precio_venta_grupo'  => round($totalVenta * $pax, 2),
            'aplica_igv_comision' => $this->aplica_igv_comision,
        ];
    }

    public function getCategoriaEtiquetaAttribute(): string
    {
        return self::CATEGORIAS[$this->categoria] ?? ucfirst($this->categoria);
    }
}

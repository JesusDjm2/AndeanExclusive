<?php

namespace App\Models;

use App\Support\Paises;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Pasajero de un programa.
 *
 * Atributos calculados (solo lectura) para las vistas:
 *  - edad:         años cumplidos a la fecha de inicio del viaje (la tarifa depende de esa edad).
 *  - nacionalidad: nombre del país (desde el código ISO en `pais`).
 *  - alimentacion: etiqueta de la dieta + alergias.
 */
class Pax extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    public const DIETAS = [
        'ninguna' => 'Sin restricción',
        'vegetariana' => 'Vegetariana',
        'vegana' => 'Vegana',
        'pescetariana' => 'Pescetariana',
        'sin_gluten' => 'Sin gluten',
        'sin_lactosa' => 'Sin lactosa',
        'kosher' => 'Kosher',
        'halal' => 'Halal',
        'otra' => 'Otra (ver alergias / notas)',
    ];

    public const DIETAS_EN = [
        'ninguna' => 'No restrictions', 'vegetariana' => 'Vegetarian', 'vegana' => 'Vegan', 'pescetariana' => 'Pescatarian',
        'sin_gluten' => 'Gluten-free', 'sin_lactosa' => 'Lactose-free', 'kosher' => 'Kosher', 'halal' => 'Halal', 'otra' => 'Other (see allergies)',
    ];

    public const SEXOS = [
        'F' => 'Femenino',
        'M' => 'Masculino',
        'X' => 'Otro / prefiere no indicar',
    ];

    /** Vigencia mínima del pasaporte al ingresar a Perú (meses). */
    public const MESES_VIGENCIA_PASAPORTE = 6;

    protected $fillable = [
        'nombre',
        'fecha_nacimiento',
        'sexo',
        'pais',
        'pasaporte',             // número
        'pasaporte_vencimiento',
        'pasaporte_archivo',     // escaneo privado (App\Support\PassportFiles)
        'dieta',
        'alergias',
        'condiciones_medicas',
        'emergencia_nombre',
        'emergencia_telefono',
        'programa_id',
        'actualizado_por_cliente_at',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'pasaporte_vencimiento' => 'date',
        'actualizado_por_cliente_at' => 'datetime',
    ];

    public function agentes()
    {
        return $this->belongsToMany(Agente::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class)->withoutGlobalScope('sin_plantillas');
    }

    /** Fecha de referencia para la edad y la vigencia: inicio del viaje o, si no hay, hoy. */
    private function fechaViaje(): Carbon
    {
        $inicio = $this->programa_id ? $this->programa?->inicio : null;

        return $inicio ? Carbon::parse($inicio) : Carbon::today();
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento ? (int) $this->fecha_nacimiento->diffInYears($this->fechaViaje()) : null;
    }

    public function getNacionalidadAttribute(): ?string
    {
        return Paises::nombre($this->pais);
    }

    public function getAlimentacionAttribute(): ?string
    {
        return $this->alimentacionEn('es');
    }

    /** Dieta + alergias en el idioma pedido (el itinerario y el formulario del cliente pueden estar en inglés). */
    public function alimentacionEn(string $locale): ?string
    {
        $etiquetas = $locale === 'en' ? self::DIETAS_EN : self::DIETAS;
        $dieta = $this->dieta && $this->dieta !== 'ninguna' ? ($etiquetas[$this->dieta] ?? $this->dieta) : null;
        $alergias = $this->alergias ? ($locale === 'en' ? 'Allergies: ' : 'Alergias: ') . $this->alergias : null;

        return collect([$dieta, $alergias])->filter()->implode(' · ') ?: null;
    }

    /**
     * Problema con el pasaporte para este viaje: 'falta' (sin número), 'vencido' o 'por_vencer'
     * (menos de 6 meses de vigencia al ingresar). null si está bien o no aplica.
     */
    public function alertaPasaporte(): ?string
    {
        if (! $this->pasaporte) {
            return 'falta';
        }
        if (! $this->pasaporte_vencimiento) {
            return null;
        }
        $viaje = $this->fechaViaje();
        if ($this->pasaporte_vencimiento->lt($viaje)) {
            return 'vencido';
        }

        return $this->pasaporte_vencimiento->lt($viaje->copy()->addMonths(self::MESES_VIGENCIA_PASAPORTE)) ? 'por_vencer' : null;
    }
}

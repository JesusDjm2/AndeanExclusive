<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/** Pendiente de un programa con fecha límite (comprar entradas, reconfirmar hoteles...). */
class Tarea extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    /**
     * Lista de control habitual de un viaje en Perú: clave => [tarea, días antes del inicio].
     * Las entradas a Machu Picchu y los permisos del Camino Inca se agotan con meses de anticipación.
     */
    public const PLANTILLAS = [
        'camino_inca' => ['Comprar permisos del Camino Inca', 180],
        'machu_picchu' => ['Comprar entradas a Machu Picchu', 90],
        'datos_pax' => ['Pedir datos y pasaportes de los pasajeros', 75],
        'tren' => ['Reservar tren a Machu Picchu', 60],
        'vuelos' => ['Emitir vuelos internos', 45],
        'hoteles' => ['Reconfirmar hoteles y rooming list', 30],
        'vouchers' => ['Enviar órdenes de servicio a proveedores', 10],
        'itinerario_final' => ['Enviar itinerario final al cliente', 7],
        'briefing' => ['Briefing con guía y chofer', 1],
    ];

    protected $fillable = ['programa_id', 'titulo', 'fecha_limite', 'notas', 'completada_at', 'completada_por'];

    protected $casts = [
        'fecha_limite' => 'date',
        'completada_at' => 'datetime',
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function completadaPor()
    {
        return $this->belongsTo(User::class, 'completada_por');
    }

    public function getCompletadaAttribute(): bool
    {
        return $this->completada_at !== null;
    }

    /** 'hecha' | 'vencida' | 'proxima' (7 días) | 'pendiente'. */
    public function situacion(): string
    {
        if ($this->completada_at) {
            return 'hecha';
        }
        if (! $this->fecha_limite) {
            return 'pendiente';
        }
        $hoy = Carbon::today();

        return match (true) {
            $this->fecha_limite->lt($hoy) => 'vencida',
            $this->fecha_limite->lte($hoy->copy()->addDays(7)) => 'proxima',
            default => 'pendiente',
        };
    }

    public function scopePendientes($query)
    {
        return $query->whereNull('completada_at');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Entrada del historial de un servicio: cambio de estado, confirmación, pago registrado o anulado, nota. */
class ServicioEvento extends Model
{
    protected $table = 'servicio_eventos';

    protected $fillable = ['servicio_id', 'user_id', 'tipo', 'de', 'a', 'detalle'];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Texto legible del evento para el historial. */
    public function getDescripcionAttribute(): string
    {
        return match ($this->tipo) {
            'estado' => ($this->de ? (Servicio::ESTADOS[$this->de] ?? $this->de) . ' → ' : '') . (Servicio::ESTADOS[$this->a] ?? $this->a),
            'pago' => 'Pago registrado',
            'pago_anulado' => 'Pago anulado',
            default => 'Nota',
        };
    }
}

<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

/** Un pago no se borra: se anula con motivo y queda como constancia, fuera de todos los totales. */
trait Anulable
{
    use SoftDeletes;

    /** Reglas del motivo, las mismas en cualquier pantalla desde la que se anule. */
    public static function reglasMotivo(): array
    {
        return [
            'reglas' => ['motivo' => 'required|string|min:5|max:255'],
            'mensajes' => [
                'motivo.required' => 'Indica el motivo de la anulación.',
                'motivo.min' => 'Explica el motivo de la anulación con un poco más de detalle.',
                'motivo.max' => 'El motivo no puede superar los 255 caracteres.',
            ],
        ];
    }

    public function anular(string $motivo): void
    {
        $this->forceFill(['anulado_por' => auth()->id(), 'motivo_anulacion' => $motivo])->saveQuietly();
        $this->delete();
    }

    public function anuladoPor()
    {
        return $this->belongsTo(User::class, 'anulado_por');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/** Un mensaje del hilo de Reservas con un proveedor: enviado desde reservas@ o respuesta registrada por Reservas. */
class MensajeProveedor extends Model
{
    protected $table = 'mensajes_proveedor';

    protected $fillable = [
        'proveedor_id', 'servicio_id', 'programa_id', 'user_id', 'direccion', 'tipo',
        'para', 'cc', 'asunto', 'cuerpo', 'con_orden', 'adjunto_nombre', 'adjunto_ruta', 'resultado', 'error',
    ];

    protected $casts = ['con_orden' => 'boolean'];

    public const TIPOS = [
        'solicitud' => 'Solicitud de reserva',
        'confirmacion' => 'Confirmación y orden',
        'modificacion' => 'Modificación',
        'cancelacion' => 'Cancelación',
        'consulta' => 'Consulta',
        'respuesta' => 'Respuesta del proveedor',
    ];

    public const RESULTADOS = [
        'confirmado' => 'Confirmó la reserva',
        'sin_disponibilidad' => 'No tiene disponibilidad',
        'otro' => 'Otra respuesta',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeEnviados(Builder $query): Builder
    {
        return $query->where('direccion', 'enviado');
    }

    public function getFallidoAttribute(): bool
    {
        return filled($this->error);
    }
}

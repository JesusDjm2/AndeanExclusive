<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Correo enviado al cliente de un programa (recordatorio de pago, pedido de datos, encuesta). */
class Notificacion extends Model
{
    use \App\Models\Concerns\RegistraMovimientos;

    protected $table = 'notificaciones';

    /** Tipo => etiqueta en el panel. */
    public const TIPOS = [
        'deposito' => 'Recordatorio de depósito',
        'saldo' => 'Recordatorio de saldo',
        'datos_pax' => 'Pedido de datos de pasajeros',
        'encuesta' => 'Encuesta de satisfacción',
    ];

    protected $fillable = ['programa_id', 'tipo', 'email', 'automatica', 'enviada_por'];

    protected $casts = ['automatica' => 'boolean'];

    public function programa()
    {
        return $this->belongsTo(Programa::class)->withoutGlobalScope('sin_plantillas');
    }

    public function enviadaPor()
    {
        return $this->belongsTo(User::class, 'enviada_por');
    }
}

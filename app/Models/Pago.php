<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Pago recibido de un programa (depósito, saldo u otro). */
class Pago extends Model
{
    use \App\Models\Concerns\Anulable, \App\Models\Concerns\RegistraMovimientos;

    public const TIPOS = [
        'deposito' => 'Depósito',
        'saldo' => 'Saldo',
        'otro' => 'Otro',
    ];

    public const METODOS = [
        'transferencia' => 'Transferencia',
        'tarjeta' => 'Tarjeta',
        'paypal' => 'PayPal',
        'efectivo' => 'Efectivo',
        'otro' => 'Otro',
    ];

    protected $fillable = ['programa_id', 'fecha', 'monto', 'tipo', 'metodo', 'referencia', 'nota'];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }
}

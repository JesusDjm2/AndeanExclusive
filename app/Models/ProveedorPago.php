<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Pago a un proveedor por un servicio (puede ser parcial). Al crearse o anularse, el servicio recalcula su saldo. */
class ProveedorPago extends Model
{
    use \App\Models\Concerns\Anulable, \App\Models\Concerns\RegistraMovimientos;

    protected $table = 'proveedor_pagos';

    public const METODOS = [
        'transferencia' => 'Transferencia',
        'deposito' => 'Depósito en cuenta',
        'efectivo' => 'Efectivo',
        'tarjeta' => 'Tarjeta',
        'yape_plin' => 'Yape / Plin',
        'otro' => 'Otro',
    ];

    protected $fillable = ['servicio_id', 'fecha', 'monto', 'moneda', 'metodo', 'referencia', 'comprobante', 'user_id'];

    protected $casts = [
        'fecha' => 'date',
        'monto' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::created(function (ProveedorPago $pago) {
            $pago->servicio->registrarEvento('pago', null, null,
                Servicio::formatoMonto((float) $pago->monto, $pago->moneda) . ($pago->referencia ? ' · ' . $pago->referencia : ''));
            $pago->servicio->recalcularPago();
        });

        // Anulado: conserva su comprobante como constancia; deja de contar para el saldo.
        static::deleted(function (ProveedorPago $pago) {
            $pago->servicio->registrarEvento('pago_anulado', null, null, Servicio::formatoMonto((float) $pago->monto, $pago->moneda)
                . ($pago->motivo_anulacion ? ' · Motivo: ' . $pago->motivo_anulacion : ''));
            $pago->servicio->recalcularPago();
        });
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

<?php

namespace App\Models\Concerns;

use App\Support\Bitacora;

/** Anota en la bitácora de la agencia cada alta, cambio y baja del modelo (qué se registra: App\Support\Bitacora). */
trait RegistraMovimientos
{
    protected static function bootRegistraMovimientos(): void
    {
        static::created(fn ($modelo) => Bitacora::registrar($modelo, 'creado'));
        static::updated(fn ($modelo) => Bitacora::registrar($modelo, 'actualizado'));
        static::deleted(fn ($modelo) => Bitacora::registrar($modelo, 'eliminado'));
    }
}

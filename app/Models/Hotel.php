<?php

namespace App\Models;

/**
 * @deprecated Los hoteles ahora se gestionan directamente como Proveedor con categoría "Hoteles".
 * Esta clase se mantiene como alias de compatibilidad para evitar roturas en referencias legadas.
 */
class Hotel extends Proveedor
{
    protected $table = 'proveedors';
}

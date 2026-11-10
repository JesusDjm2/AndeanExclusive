<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class TarifarioAjuste extends Model
{
    protected $table = 'tarifario_ajustes';

    protected $fillable = [
        'clave',
        'valor',
        'descripcion',
    ];

    public static function get(string $clave, mixed $default = null): mixed
    {
        try {
            return Cache::remember("tarifario_ajuste_{$clave}", 3600, function () use ($clave, $default) {
                $ajuste = static::where('clave', $clave)->first();
                return $ajuste ? $ajuste->valor : $default;
            });
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $clave, mixed $valor, ?string $descripcion = null): static
    {
        Cache::forget("tarifario_ajuste_{$clave}");

        return static::updateOrCreate(
            ['clave' => $clave],
            ['valor' => (string) $valor, 'descripcion' => $descripcion]
        );
    }

    /**
     * Retorna el Tipo de Cambio oficial de referencia del Tarifario Maestro.
     */
    public static function tipoCambio(): float
    {
        $val = static::get('tipo_cambio', '3.30');
        return is_numeric($val) && (float) $val > 0 ? (float) $val : 3.30;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TarifarioCategoria extends Model
{
    protected $table = 'tarifario_categorias';

    protected $fillable = [
        'slug',
        'nombre',
        'icono',
        'orden',
        'activo',
    ];

    protected $casts = [
        'orden'  => 'integer',
        'activo' => 'boolean',
    ];

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    public function servicios(): HasMany
    {
        return $this->hasMany(TarifarioServicio::class, 'categoria', 'slug');
    }

    public function getServiciosCountAttribute(): int
    {
        return $this->servicios()->count();
    }

    /**
     * Retorna un mapa asociativo [slug => nombre] de todas las categorías activas.
     * Si la tabla no estuviera disponible, recurre a TarifarioServicio::CATEGORIAS como fallback.
     */
    public static function mapa(): array
    {
        try {
            $categorias = static::activos()->orderBy('orden')->pluck('nombre', 'slug')->toArray();
            if (! empty($categorias)) {
                return $categorias;
            }
        } catch (\Throwable $e) {
            // Fallback si la tabla no existe aún en alguna prueba
        }

        return TarifarioServicio::CATEGORIAS;
    }

    /**
     * Retorna todas las categorías ordenadas (incluyendo cantidad de servicios cargados).
     */
    public static function ordenadas(): Collection
    {
        return static::activos()
            ->orderBy('orden')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Helper para generar un slug seguro y único a partir del nombre.
     */
    public static function generarSlug(string $nombre, ?int $exceptId = null): string
    {
        $base = Str::slug($nombre);
        if ($base === '') {
            $base = 'cat';
        }

        $slug = $base;
        $counter = 1;

        while (static::where('slug', $slug)->when($exceptId, fn($q) => $q->where('id', '!=', $exceptId))->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}

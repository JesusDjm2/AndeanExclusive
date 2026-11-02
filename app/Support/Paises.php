<?php

namespace App\Support;

/** Catálogo de países (ISO 3166-1 alfa-2) en español: resources/data/paises.php. */
class Paises
{
    private static ?array $all = null;

    /** @return array<string, string> código => nombre, ordenado alfabéticamente. */
    public static function all(): array
    {
        return self::$all ??= require resource_path('data/paises.php');
    }

    /** @return array<string, string> Nombres en el idioma pedido (inglés con la extensión intl; si no, español). */
    public static function enIdioma(string $locale): array
    {
        if ($locale === 'es' || ! class_exists(\Locale::class)) {
            return self::all();
        }
        $nombres = [];
        foreach (array_keys(self::all()) as $codigo) {
            $nombres[$codigo] = \Locale::getDisplayRegion('-' . $codigo, $locale) ?: self::all()[$codigo];
        }
        asort($nombres, SORT_NATURAL | SORT_FLAG_CASE);

        return $nombres;
    }

    public static function nombre(?string $codigo): ?string
    {
        return $codigo ? (self::all()[strtoupper($codigo)] ?? $codigo) : null;
    }

    public static function existe(?string $codigo): bool
    {
        return $codigo !== null && isset(self::all()[strtoupper($codigo)]);
    }

    /** Países más frecuentes del negocio, para mostrarlos primero en los selectores. */
    public const FRECUENTES = ['US', 'CA', 'GB', 'DE', 'FR', 'ES', 'IT', 'AU', 'BR', 'MX', 'AR', 'CL', 'PE'];
}

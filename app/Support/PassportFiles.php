<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Archivos de pasaporte de los pasajeros: se guardan fuera de public/ (storage/app/private/pasaportes)
 * y solo se sirven a usuarios del panel vía PaxController@pasaporte.
 *
 * Compatibilidad con las dos ubicaciones antiguas (ambas públicas, ahora bloqueadas):
 *  - 'img/programas/paxs/…'  → public/img/programas/paxs (ProgramaController)
 *  - 'pasaportes/…'          → storage/app/public/pasaportes (PaxController)
 */
class PassportFiles
{
    private const DIR = 'private/pasaportes';

    /** Guarda el archivo con nombre aleatorio y devuelve la ruta a persistir en paxs.pasaporte. */
    public static function store(UploadedFile $file): string
    {
        return Storage::disk('local')->putFile(self::DIR, $file);
    }

    /** Ruta absoluta del archivo (nueva o antigua) o null si no existe. */
    public static function absolutePath(?string $stored): ?string
    {
        if (! $stored || str_contains($stored, '..')) {
            return null;
        }

        $candidates = match (true) {
            str_starts_with($stored, 'private/') => [storage_path('app/' . $stored)],
            str_starts_with($stored, 'img/') => [public_path($stored)],
            default => [storage_path('app/public/' . ltrim($stored, '/'))],
        };

        foreach ($candidates as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    public static function delete(?string $stored): void
    {
        $path = self::absolutePath($stored);
        if ($path) {
            @unlink($path);
        }
    }
}

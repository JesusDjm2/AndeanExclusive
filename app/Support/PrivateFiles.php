<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/** Archivos privados (fuera de public/): se guardan con nombre aleatorio y solo se sirven por rutas autenticadas. */
class PrivateFiles
{
    public static function store(UploadedFile $file, string $carpeta): string
    {
        return Storage::disk('local')->putFile('private/' . trim($carpeta, '/'), $file);
    }

    public static function absolutePath(?string $stored): ?string
    {
        if (! $stored || str_contains($stored, '..') || ! str_starts_with($stored, 'private/')) {
            return null;
        }
        $path = storage_path('app/' . $stored);

        return is_file($path) ? $path : null;
    }

    public static function delete(?string $stored): void
    {
        if ($path = self::absolutePath($stored)) {
            @unlink($path);
        }
    }
}

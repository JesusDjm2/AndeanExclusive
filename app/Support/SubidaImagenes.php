<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Subida de imágenes a public/: aquí se decide qué se acepta y con qué nombre se guarda.
 * Lo que entra a public/ queda accesible desde internet, por eso se comprueba el contenido real del archivo y no su nombre.
 */
class SubidaImagenes
{
    /** Sin SVG: puede llevar scripts. */
    private const TIPOS = 'file|image|mimes:jpg,jpeg,png,webp,gif';

    public static function regla(int $maxMb = 2): string
    {
        return self::TIPOS . '|max:' . ($maxMb * 1024);
    }

    /** Mensajes en español para los campos de imagen indicados. */
    public static function mensajes(array $campos, int $maxMb = 2): array
    {
        $tipos = 'Solo se permiten imágenes (JPG, PNG, WebP o GIF).';
        $mensajes = [];
        foreach ($campos as $campo) {
            $mensajes["$campo.file"] = $tipos;
            $mensajes["$campo.image"] = $tipos;
            $mensajes["$campo.mimes"] = $tipos;
            $mensajes["$campo.max"] = "La imagen no puede pesar más de {$maxMb} MB.";
            $mensajes["$campo.uploaded"] = "No se pudo subir la imagen: revisa que no pese más de {$maxMb} MB.";
        }

        return $mensajes;
    }

    /**
     * Conserva el nombre original (es útil para SEO) sin caracteres raros ni rutas,
     * y con la extensión que corresponde al contenido real del archivo, nunca la que trae el nombre.
     */
    public static function nombreSeguro(UploadedFile $img): string
    {
        $base = pathinfo(str_replace('\\', '/', $img->getClientOriginalName()), PATHINFO_FILENAME);
        $base = trim((string) preg_replace('/[^A-Za-z0-9_-]+/', '-', Str::ascii($base)), '-_');

        return Str::limit($base ?: 'imagen-' . Str::random(8), 150, '') . '.' . $img->guessExtension();
    }

    /** Guarda la imagen ya validada en esa carpeta de public/ y devuelve el nombre del archivo. */
    public static function guardar(UploadedFile $img, string $carpeta, string $prefijo = ''): string
    {
        $nombre = $prefijo . self::nombreSeguro($img);
        File::ensureDirectoryExists(public_path($carpeta));
        $img->move(public_path($carpeta), $nombre);

        return $nombre;
    }
}

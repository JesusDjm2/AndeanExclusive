<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Copias de la base de datos en una carpeta privada del servidor, comprimidas.
 * Se hacen desde PHP porque en un hosting compartido mysqldump puede no estar disponible.
 */
class Respaldos
{
    private const FILAS_POR_INSERT = 200;

    public static function carpeta(): string
    {
        return storage_path('app/private/respaldos');
    }

    public static function crear(): string
    {
        File::ensureDirectoryExists(self::carpeta());
        $ruta = self::carpeta() . '/base-' . now()->format('Y-m-d-His') . '.sql.gz';
        $salida = gzopen($ruta, 'wb6');
        $pdo = DB::connection()->getPdo();

        gzwrite($salida, "-- Copia de Andean Exclusive · " . now()->toDateTimeString() . "\nSET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

        foreach (DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"') as $fila) {
            $tabla = array_values((array) $fila)[0];
            $creacion = array_values((array) DB::selectOne("SHOW CREATE TABLE `{$tabla}`"))[1];
            gzwrite($salida, "DROP TABLE IF EXISTS `{$tabla}`;\n{$creacion};\n\n");

            $lote = [];
            foreach (DB::table($tabla)->cursor() as $registro) {
                $lote[] = '(' . collect((array) $registro)->map(fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v))->implode(',') . ')';
                if (count($lote) === self::FILAS_POR_INSERT) {
                    gzwrite($salida, "INSERT INTO `{$tabla}` VALUES\n" . implode(",\n", $lote) . ";\n");
                    $lote = [];
                }
            }
            if ($lote) {
                gzwrite($salida, "INSERT INTO `{$tabla}` VALUES\n" . implode(",\n", $lote) . ";\n");
            }
            gzwrite($salida, "\n");
        }

        gzwrite($salida, "SET FOREIGN_KEY_CHECKS=1;\n");
        gzclose($salida);

        self::conservarLasRecientes();

        return $ruta;
    }

    /** @return Collection<int, array{nombre: string, bytes: int, fecha: int}> */
    public static function listar(): Collection
    {
        File::ensureDirectoryExists(self::carpeta());

        return collect(File::files(self::carpeta()))
            ->filter(fn ($archivo) => str_ends_with($archivo->getFilename(), '.sql.gz'))
            ->map(fn ($archivo) => ['nombre' => $archivo->getFilename(), 'bytes' => $archivo->getSize(), 'fecha' => $archivo->getMTime()])
            ->sortByDesc('nombre')->values();
    }

    /** Ruta de un respaldo por su nombre, sin permitir salir de la carpeta. */
    public static function ruta(string $nombre): ?string
    {
        if (! preg_match('/^base-[0-9-]+\.sql\.gz$/', $nombre) || ! is_file(self::carpeta() . '/' . $nombre)) {
            return null;
        }

        return self::carpeta() . '/' . $nombre;
    }

    private static function conservarLasRecientes(): void
    {
        self::listar()->slice((int) config('seguridad.respaldos_a_conservar'))
            ->each(fn ($respaldo) => File::delete(self::carpeta() . '/' . $respaldo['nombre']));
    }
}

<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

/**
 * Imagen de portada de los programas (PDF del cliente e itinerario web).
 * Las opciones salen de la galería y los fondos del sitio, solo imágenes reales con resolución suficiente.
 * Para el PDF se compone un JPEG A4 (recorte + degradado hacia el fondo oscuro, que Dompdf no sabe dibujar) y se guarda en caché.
 */
class Portadas
{
    public const DEFECTO = 'img/Fondos/Recorrido-Cusco-Machu-Picchu.webp';

    /** Carpetas de public/ donde se buscan imágenes. */
    private const CARPETAS = ['img/galeria', 'img/Fondos'];

    private const EXTENSIONES = ['jpg', 'jpeg', 'png', 'webp'];

    /** Afiches con texto incrustado: no sirven de fondo aunque tengan buena resolución. */
    private const EXCLUIDAS = ['semana-santa-cusco'];

    // Página A4 a 150 ppp; la foto ocupa la parte superior y se funde con el fondo.
    private const PAGINA_ANCHO = 1240;

    private const PAGINA_ALTO = 1754;

    private const FOTO_ALTO = 1263;

    private const FONDO_RGB = [11, 29, 31]; // #0b1d1f, el mismo fondo oscuro del itinerario web

    /** Sube si cambia la composición: invalida las portadas ya generadas. */
    private const VERSION = 1;

    /**
     * Imágenes elegibles, primero las de mejor calidad.
     *
     * @return array<int, array{ruta: string, nombre: string, ancho: int, alto: int, calidad: string}>
     */
    public static function opciones(): array
    {
        return Cache::remember('portadas.opciones.v' . self::VERSION, now()->addHour(), function () {
            $opciones = [];
            $vistas = [];

            foreach (self::CARPETAS as $carpeta) {
                foreach (File::files(public_path($carpeta)) as $archivo) {
                    if (! in_array(strtolower($archivo->getExtension()), self::EXTENSIONES, true)) {
                        continue;
                    }
                    $info = @getimagesize($archivo->getPathname());
                    if (! $info) {
                        continue;
                    }
                    [$ancho, $alto] = $info;
                    $calidad = self::calidad($ancho, $alto);
                    $base = pathinfo($archivo->getFilename(), PATHINFO_FILENAME);
                    if (! $calidad || in_array($base, self::EXCLUIDAS, true)) {
                        continue;
                    }
                    // Misma foto con otro nombre, formato o carpeta: una sola opción.
                    $huella = self::huella($archivo->getPathname());
                    if ($huella === null || collect($vistas)->contains(fn ($vista) => levenshtein($vista, $huella) <= 6)) {
                        continue;
                    }
                    $vistas[] = $huella;
                    $opciones[] = [
                        'ruta' => $carpeta . '/' . $archivo->getFilename(),
                        'nombre' => ucfirst(trim(preg_replace('/\s+/', ' ', preg_replace('/\b(\d+|\d*zon)\b/', ' ', str_replace(['-', '_'], ' ', $base))))),
                        'ancho' => $ancho,
                        'alto' => $alto,
                        'calidad' => $calidad,
                    ];
                }
            }

            usort($opciones, fn ($a, $b) => [$a['calidad'] !== 'alta', -$a['alto']] <=> [$b['calidad'] !== 'alta', -$b['alto']]);

            return $opciones;
        });
    }

    /** Huella visual (promedio de 8×8 en grises): dos archivos de la misma foto dan la misma huella. */
    private static function huella(string $ruta): ?string
    {
        $contenido = @file_get_contents($ruta);
        $imagen = $contenido ? @imagecreatefromstring($contenido) : false;
        if (! $imagen) {
            return null;
        }
        $mini = imagecreatetruecolor(8, 8);
        imagecopyresampled($mini, $imagen, 0, 0, 0, 0, 8, 8, imagesx($imagen), imagesy($imagen));

        $grises = [];
        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $c = imagecolorat($mini, $x, $y);
                $grises[] = (($c >> 16) & 255) * 0.3 + (($c >> 8) & 255) * 0.59 + ($c & 255) * 0.11;
            }
        }
        $promedio = array_sum($grises) / 64;

        return implode('', array_map(fn ($g) => $g > $promedio ? '1' : '0', $grises));
    }

    public static function rutas(): array
    {
        return array_column(self::opciones(), 'ruta');
    }

    /**
     * Cuánto hay que ampliar la foto para llenar la zona de imagen de la portada.
     * 'alta' = se ve nítida; 'media' = aceptable; null = se vería borrosa, no se ofrece.
     */
    private static function calidad(int $ancho, int $alto): ?string
    {
        $ampliacion = max(self::PAGINA_ANCHO / max(1, $ancho), self::FOTO_ALTO / max(1, $alto));

        return match (true) {
            $ampliacion <= 1.25 => 'alta',
            $ampliacion <= 1.8 => 'media',
            default => null,
        };
    }

    /** Ruta a usar: la elegida si sigue existiendo; si no, la de por defecto. */
    public static function resolver(?string $ruta): string
    {
        return $ruta && in_array($ruta, self::rutas(), true) && is_file(public_path($ruta)) ? $ruta : self::DEFECTO;
    }

    /** Portada compuesta para el PDF como data URI, o null si no se puede generar (el PDF usa entonces fondo oscuro liso). */
    public static function paraPdf(?string $ruta): ?string
    {
        $origen = public_path(self::resolver($ruta));
        if (! extension_loaded('gd') || ! is_file($origen)) {
            return null;
        }

        $destino = storage_path('app/portadas/' . md5($origen . filemtime($origen) . self::VERSION) . '.jpg');
        if (! is_file($destino) && ! self::componer($origen, $destino)) {
            return null;
        }

        return 'data:image/jpeg;base64,' . base64_encode((string) file_get_contents($destino));
    }

    private static function componer(string $origen, string $destino): bool
    {
        $contenido = @file_get_contents($origen);
        $foto = $contenido ? @imagecreatefromstring($contenido) : false;
        if (! $foto) {
            return false;
        }

        [$r, $g, $b] = self::FONDO_RGB;
        $lienzo = imagecreatetruecolor(self::PAGINA_ANCHO, self::PAGINA_ALTO);
        imagefill($lienzo, 0, 0, imagecolorallocate($lienzo, $r, $g, $b));

        // Recorte centrado para llenar la zona de la foto sin deformarla.
        $fw = imagesx($foto);
        $fh = imagesy($foto);
        $escala = max(self::PAGINA_ANCHO / $fw, self::FOTO_ALTO / $fh);
        $recorteW = (int) round(self::PAGINA_ANCHO / $escala);
        $recorteH = (int) round(self::FOTO_ALTO / $escala);
        imagecopyresampled($lienzo, $foto, 0, 0, (int) (($fw - $recorteW) / 2), (int) (($fh - $recorteH) / 2),
            self::PAGINA_ANCHO, self::FOTO_ALTO, $recorteW, $recorteH);
        imagedestroy($foto);

        // Velo superior para que el logo se lea sobre cielos claros.
        imagealphablending($lienzo, true);
        $velo = 260;
        for ($y = 0; $y < $velo; $y++) {
            $opacidad = 0.55 * (1 - $y / $velo);
            imageline($lienzo, 0, $y, self::PAGINA_ANCHO, $y, imagecolorallocatealpha($lienzo, $r, $g, $b, (int) round(127 * (1 - $opacidad))));
        }

        // Degradado inferior: la foto se funde con el fondo donde va la información.
        $inicio = 480;
        for ($y = $inicio; $y < self::FOTO_ALTO; $y++) {
            $t = ($y - $inicio) / (self::FOTO_ALTO - $inicio);
            $opacidad = $t * $t * (3 - 2 * $t); // curva suave, sin línea visible de corte
            imageline($lienzo, 0, $y, self::PAGINA_ANCHO, $y, imagecolorallocatealpha($lienzo, $r, $g, $b, (int) round(127 * (1 - $opacidad))));
        }

        File::ensureDirectoryExists(dirname($destino));
        $ok = imagejpeg($lienzo, $destino, 84);
        imagedestroy($lienzo);

        return $ok;
    }
}

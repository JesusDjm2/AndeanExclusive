<?php

/**
 * Genera JPG para el PDF (Dompdf no necesita GD con JPEG).
 * Origen: public/img/logo2.png → public/img/logo2-pdf.jpg
 *
 * Ejecutar: php scripts/convert-logo-for-pdf.php
 */

if (! extension_loaded('gd')) {
    fwrite(STDERR, "GD no está disponible en esta CLI. Habilita extension=gd en php.ini.\n");
    exit(1);
}

$base = dirname(__DIR__);

$jobs = [
    [
        'src' => $base . '/public/img/logo2.png',
        'dst' => $base . '/public/img/logo2-pdf.jpg',
    ],
];

$ok = false;
foreach ($jobs as $job) {
    $png = $job['src'];
    $out = $job['dst'];

    if (! is_file($png)) {
        echo "Omitido (no existe): {$png}\n";
        continue;
    }

    $im = @imagecreatefrompng($png);
    if (! $im) {
        fwrite(STDERR, "No se pudo leer: {$png}\n");
        continue;
    }

    $w = imagesx($im);
    $h = imagesy($im);
    $bg = imagecreatetruecolor($w, $h);
    $white = imagecolorallocate($bg, 255, 255, 255);
    imagefill($bg, 0, 0, $white);
    imagecopy($bg, $im, 0, 0, 0, 0, $w, $h);
    imagejpeg($bg, $out, 92);
    imagedestroy($im);
    imagedestroy($bg);

    echo "Creado: {$out}\n";
    $ok = true;
}

exit($ok ? 0 : 1);

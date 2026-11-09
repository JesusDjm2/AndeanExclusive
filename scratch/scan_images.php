<?php
$dirs = [
    'public/img',
    'public/img/banners',
    'public/img/tours',
    'public/img/galeria',
    'public/img/blog',
    'public/images',
    'storage/app/public',
    'storage/app/public/tours',
    'storage/app/public/categorias',
    'storage/app/public/portadas',
];

$base = 'c:/xampp/htdocs/AndeanExclusive/';

foreach ($dirs as $rel) {
    $full = $base . $rel;
    if (!is_dir($full)) continue;
    $files = scandir($full);
    $found = 0;
    $hiRes = [];
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $p = $full . '/' . $f;
        if (!is_file($p)) continue;
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) continue;
        $found++;
        $size = filesize($p);
        $img = @getimagesize($p);
        $w = $img ? $img[0] : 0;
        $h = $img ? $img[1] : 0;
        if ($w >= 1400) {
            $hiRes[] = ['rel' => $rel . '/' . $f, 'w' => $w, 'h' => $h, 'kb' => round($size/1024, 1)];
        }
    }
    echo "Folder: $rel (Total images: $found, Hi-Res >=1400px: " . count($hiRes) . ")\n";
    foreach ($hiRes as $item) {
        echo sprintf("   %-55s %4dx%-4d %6.1f KB\n", $item['rel'], $item['w'], $item['h'], $item['kb']);
    }
}

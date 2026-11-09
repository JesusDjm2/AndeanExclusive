<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$publicDir = 'c:/xampp/htdocs/AndeanExclusive/public/';

echo "=== ESBLOGS (Spanish) ===\n";
$esblogs = DB::table('esblogs')->get();
foreach ($esblogs as $b) {
    $thumb = $b->imgThumb ? $publicDir . ltrim($b->imgThumb, '/') : null;
    $full = $b->imgFull ? $publicDir . ltrim($b->imgFull, '/') : null;
    $tInfo = $thumb && file_exists($thumb) ? getimagesize($thumb) : null;
    $fInfo = $full && file_exists($full) ? getimagesize($full) : null;
    $tDim = $tInfo ? "{$tInfo[0]}x{$tInfo[1]}" : "MISSING/UNKNOWN";
    $fDim = $fInfo ? "{$fInfo[0]}x{$fInfo[1]}" : "MISSING/UNKNOWN";
    echo sprintf("ID %2d: %-38s | Thumb (%s): %s | Full (%s): %s\n",
        $b->id,
        substr($b->nombre, 0, 38),
        $tDim,
        $b->imgThumb,
        $fDim,
        $b->imgFull
    );
}

echo "\n=== ENBLOGS (English) ===\n";
$enblogs = DB::table('enblogs')->get();
foreach ($enblogs as $b) {
    $thumb = $b->imgThumb ? $publicDir . ltrim($b->imgThumb, '/') : null;
    $full = $b->imgFull ? $publicDir . ltrim($b->imgFull, '/') : null;
    $tInfo = $thumb && file_exists($thumb) ? getimagesize($thumb) : null;
    $fInfo = $full && file_exists($full) ? getimagesize($full) : null;
    $tDim = $tInfo ? "{$tInfo[0]}x{$tInfo[1]}" : "MISSING/UNKNOWN";
    $fDim = $fInfo ? "{$fInfo[0]}x{$fInfo[1]}" : "MISSING/UNKNOWN";
    echo sprintf("ID %2d: %-38s | Thumb (%s): %s | Full (%s): %s\n",
        $b->id,
        substr($b->nombre, 0, 38),
        $tDim,
        $b->imgThumb,
        $fDim,
        $b->imgFull
    );
}

<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$baseDir = 'c:/xampp/htdocs/AndeanExclusive/public/';

// 1. Copy/rename thumb images
$srcJpg = $baseDir . 'img/thumb/corpus-cristi-San Cristobal-Andean-Exclusive.jpg';
$dstJpg = $baseDir . 'img/thumb/corpus-cristi-san-cristobal-andean-exclusive.jpg';
if (file_exists($srcJpg)) {
    copy($srcJpg, $dstJpg);
    echo "Copied to $dstJpg\n";
}

$srcWebp = $baseDir . 'img/thumb/corpus-cristi-San Cristobal-Andean-Exclusive.webp';
$dstWebp = $baseDir . 'img/thumb/corpus-cristi-san-cristobal-andean-exclusive.webp';
if (file_exists($srcWebp)) {
    copy($srcWebp, $dstWebp);
    echo "Copied to $dstWebp\n";
}

// 2. Copy/rename full image
$srcLima = $baseDir . 'img/full/centro historico-lima2 (1).webp';
$dstLima = $baseDir . 'img/full/centro-historico-lima2.webp';
if (file_exists($srcLima)) {
    copy($srcLima, $dstLima);
    echo "Copied to $dstLima\n";
}

// 3. Update database
DB::table('enblogs')->where('id', 3)->update([
    'imgThumb' => 'img/thumb/corpus-cristi-san-cristobal-andean-exclusive.webp'
]);
echo "Updated enblogs ID 3\n";

DB::table('esblogs')->where('id', 14)->update([
    'imgThumb' => 'img/thumb/corpus-cristi-san-cristobal-andean-exclusive.jpg'
]);
echo "Updated esblogs ID 14\n";

DB::table('enblogs')->where('id', 4)->update([
    'imgFull' => 'img/full/centro-historico-lima2.webp'
]);
echo "Updated enblogs ID 4\n";

DB::table('esblogs')->where('id', 13)->update([
    'imgFull' => 'img/full/centro-historico-lima2.webp'
]);
echo "Updated esblogs ID 13\n";

echo "Done normalizing image names!\n";

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Enblog;
use App\Models\Esblog;

$allEn = Enblog::all();
$allEs = Esblog::all();

echo "Checking embedded images in all EN & ES blogs...\n";
foreach ($allEn as $b) {
    preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $b->descripcion, $m);
    if (!empty($m[1])) {
        echo "EN ID {$b->id} ({$b->slug}): " . count($m[1]) . " images\n";
        foreach ($m[1] as $src) echo "  $src\n";
    }
}
foreach ($allEs as $b) {
    preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $b->descripcion, $m);
    if (!empty($m[1])) {
        echo "ES ID {$b->id} ({$b->slug}): " . count($m[1]) . " images\n";
        foreach ($m[1] as $src) echo "  $src\n";
    }
}
echo "Done checking embedded images.\n";

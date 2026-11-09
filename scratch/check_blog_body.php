<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Enblog;

$sample = Enblog::first();
echo "Blog: {$sample->nombre}\n";
echo "Desc length: " . strlen($sample->descripcion) . "\n";
echo "Preview:\n" . substr(strip_tags($sample->descripcion), 0, 300) . "...\n";

// Check if there are img tags inside descripcion
preg_match_all('/<img[^>]+>/i', $sample->descripcion, $imgs);
echo "Images inside descripcion: " . count($imgs[0]) . "\n";
foreach ($imgs[0] as $im) {
    echo "  $im\n";
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Enblog;
use App\Models\Esblog;

$allEn = Enblog::all();
$allEs = Esblog::all();
$missing = [];
$total = 0;

$checkSrc = function($src) use (&$missing, &$total) {
    $total++;
    // Extract path after domain or relative
    $parsed = parse_url($src, PHP_URL_PATH);
    $rel = ltrim($parsed, '/');
    $local = public_path($rel);
    if (!file_exists($local)) {
        $missing[] = [
            'src' => $src,
            'rel' => $rel,
            'local' => $local,
        ];
    }
};

foreach ($allEn as $b) {
    preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $b->descripcion, $m);
    foreach ($m[1] as $src) $checkSrc($src);
}
foreach ($allEs as $b) {
    preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $b->descripcion, $m);
    foreach ($m[1] as $src) $checkSrc($src);
}

echo "Total embedded images checked: $total\n";
echo "Missing files: " . count($missing) . "\n";
foreach (array_unique(array_column($missing, 'src')) as $m) {
    echo "  MISSING: $m\n";
}

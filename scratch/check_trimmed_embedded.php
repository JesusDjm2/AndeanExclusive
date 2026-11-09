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
$trailingSpacesFound = 0;

$checkSrc = function($rawSrc) use (&$missing, &$total, &$trailingSpacesFound) {
    $total++;
    $src = trim($rawSrc);
    if ($src !== $rawSrc) {
        $trailingSpacesFound++;
    }
    // Clean URL
    $srcClean = rawurldecode($src);
    $parsed = parse_url($srcClean, PHP_URL_PATH);
    $rel = ltrim($parsed, '/');
    $local = public_path($rel);
    if (!file_exists($local)) {
        $missing[] = [
            'src' => $rawSrc,
            'clean' => $srcClean,
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

echo "Total embedded images: $total\n";
echo "Trailing spaces found in HTML src: $trailingSpacesFound\n";
echo "Actually missing on disk after trim/urldecode: " . count($missing) . "\n";
foreach (array_unique(array_column($missing, 'clean')) as $m) {
    echo "  STILL MISSING: $m\n";
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Programa::whereNotNull('token')->first();
if ($p) {
    echo "Found token: " . $p->token . "\n";
    $url = "https://andean.pachamamaspirit.com/itinerario/" . $p->token;
    echo "Testing URL: $url\n";
    
    $ctx = stream_context_create(['http' => ['timeout' => 15, 'ignore_errors' => true]]);
    $html = @file_get_contents($url, false, $ctx);
    
    if ($html) {
        echo "Response length: " . strlen($html) . "\n";
        echo "Has data-itin-day: " . (strpos($html, 'data-itin-day') !== false ? 'YES' : 'NO') . "\n";
        echo "Has data-itin-expand-all: " . (strpos($html, 'data-itin-expand-all') !== false ? 'YES' : 'NO') . "\n";
        echo "Has programa-547VcHki.css: " . (strpos($html, 'programa-547VcHki.css') !== false ? 'YES' : 'NO') . "\n";
        echo "Has programa-CsUXiCuY.js: " . (strpos($html, 'programa-CsUXiCuY.js') !== false ? 'YES' : 'NO') . "\n";
    } else {
        echo "Failed to fetch remote page.\n";
    }
} else {
    echo "No program with token found locally.\n";
}

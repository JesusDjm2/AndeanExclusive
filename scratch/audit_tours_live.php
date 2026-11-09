<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$ctx = stream_context_create([
    'http' => ['timeout' => 15, 'user_agent' => 'Mozilla/5.0'],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);

$tours = DB::table('tours')->get();
$estours = DB::table('estours')->get();

echo "Testing English tours...\n";
foreach ($tours as $t) {
    $url = "https://andean.pachamamaspirit.com/en/{$t->slug}";
    $html = @file_get_contents($url, false, $ctx);
    if (!$html) {
        echo "  [FAIL 404/ERR] $url\n";
        continue;
    }
    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m);
    foreach ($m[1] as $img) {
        if (str_starts_with($img, 'data:') || $img === '#') continue;
        $full = str_starts_with($img, 'http') ? $img : 'https://andean.pachamamaspirit.com/' . ltrim($img, '/');
        $h = @get_headers($full, false, $ctx);
        if ($h && preg_match('/HTTP\/\S+\s+(\d+)/', $h[0], $match)) {
            $code = (int)$match[1];
            if ($code >= 400) {
                echo "  [BROKEN $code] $full on tour {$t->slug}\n";
            }
        }
    }
}

echo "\nTesting Spanish estours...\n";
foreach ($estours as $t) {
    $url = "https://andean.pachamamaspirit.com/{$t->slug}";
    $html = @file_get_contents($url, false, $ctx);
    if (!$html) {
        echo "  [FAIL 404/ERR] $url\n";
        continue;
    }
    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m);
    foreach ($m[1] as $img) {
        if (str_starts_with($img, 'data:') || $img === '#') continue;
        $full = str_starts_with($img, 'http') ? $img : 'https://andean.pachamamaspirit.com/' . ltrim($img, '/');
        $h = @get_headers($full, false, $ctx);
        if ($h && preg_match('/HTTP\/\S+\s+(\d+)/', $h[0], $match)) {
            $code = (int)$match[1];
            if ($code >= 400) {
                echo "  [BROKEN $code] $full on estour {$t->slug}\n";
            }
        }
    }
}
echo "Tour audit complete.\n";

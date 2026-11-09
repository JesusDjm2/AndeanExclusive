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

$esblogs = DB::table('esblogs')->get();
$enblogs = DB::table('enblogs')->get();

echo "Testing English blogs (" . $enblogs->count() . ")...\n";
foreach ($enblogs as $b) {
    $url = "https://andean.pachamamaspirit.com/en/blog/{$b->slug}";
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
                echo "  [BROKEN $code] $full on blog {$b->slug}\n";
            }
        }
    }
}

echo "\nTesting Spanish esblogs (" . $esblogs->count() . ")...\n";
foreach ($esblogs as $b) {
    $url = "https://andean.pachamamaspirit.com/blog/{$b->slug}";
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
                echo "  [BROKEN $code] $full on esblog {$b->slug}\n";
            }
        }
    }
}
echo "Blog audit complete.\n";

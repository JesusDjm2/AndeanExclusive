<?php
$publicDir = 'c:/xampp/htdocs/AndeanExclusive/public/';
foreach (['resources/views/index.blade.php', 'resources/views/index-castellano.blade.php'] as $view) {
    $content = file_get_contents('c:/xampp/htdocs/AndeanExclusive/' . $view);
    preg_match_all('/(?:asset\([\'"]|src=[\'"])([^\'"\?\#]+\.(?:jpg|jpeg|png|webp|svg|gif))[\'"]/i', $content, $m);
    echo "Checking $view (" . count($m[1]) . " static images):\n";
    foreach ($m[1] as $ref) {
        if (str_contains($ref, '$') || str_contains($ref, '{{') || str_starts_with($ref, 'http')) continue;
        $clean = ltrim($ref, '/');
        if (!file_exists($publicDir . $clean)) {
            echo "   BROKEN: $clean\n";
        }
    }
}

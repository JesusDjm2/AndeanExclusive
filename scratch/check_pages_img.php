<?php
$baseDir = 'c:/xampp/htdocs/AndeanExclusive';
$pagesDir = $baseDir . '/resources/views/pages';
$publicDir = $baseDir . '/public';

$files = glob($pagesDir . '/*.blade.php');
foreach ($files as $f) {
    $content = file_get_contents($f);
    preg_match_all('/(?:asset\([\'"]|src=[\'"])([^\'"\?\#]+\.(?:jpg|jpeg|png|webp|svg|gif))[\'"]/i', $content, $m);
    foreach ($m[1] as $ref) {
        if (str_contains($ref, '$') || str_contains($ref, '{{') || str_starts_with($ref, 'http')) continue;
        $clean = ltrim($ref, '/');
        if (!file_exists($publicDir . '/' . $clean)) {
            echo "Broken in " . basename($f) . ": $clean\n";
        }
    }
}
echo "Pages check complete.\n";

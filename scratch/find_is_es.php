<?php
$dir = 'c:/xampp/htdocs/AndeanExclusive/resources/views';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($rii as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') continue;
    $content = file_get_contents($file->getPathname());
    $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($dir) + 1));
    
    // Check for $isEs or $locale assignment
    if (preg_match_all('/\$isEs\s*=[^;]+;/s', $content, $matches)) {
        echo "=== \$isEs in $rel ===\n";
        foreach ($matches[0] as $m) {
            echo trim($m) . "\n";
        }
        echo "\n";
    }
}

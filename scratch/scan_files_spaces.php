<?php
$baseDir = 'c:/xampp/htdocs/AndeanExclusive/public/img';

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir));
$withSpaces = [];
foreach ($it as $file) {
    if ($file->isFile()) {
        $name = $file->getFilename();
        if (str_contains($name, ' ') || str_contains($name, '(') || str_contains($name, ')')) {
            $withSpaces[] = str_replace('c:/xampp/htdocs/AndeanExclusive/public/', '', $file->getPathname());
        }
    }
}

echo "Files in public/img with spaces or parentheses: " . count($withSpaces) . "\n";
foreach ($withSpaces as $f) {
    echo "  - $f\n";
}

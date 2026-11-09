<?php
$base = 'c:/xampp/htdocs/AndeanExclusive/resources/views';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
foreach ($it as $f) {
    if ($f->isFile() && str_ends_with($f->getFilename(), '.blade.php')) {
        $c = file_get_contents($f->getPathname());
        if (str_contains($c, 'book AET') || str_contains($c, 'valores AET')) {
            echo "Referenced in: " . $f->getPathname() . "\n";
        }
    }
}

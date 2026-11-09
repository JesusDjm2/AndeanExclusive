<?php
$base = 'c:/xampp/htdocs/AndeanExclusive/resources/views';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base));
foreach ($it as $f) {
    if ($f->isFile() && str_contains($f->getFilename(), 'blog')) {
        echo str_replace($base . DIRECTORY_SEPARATOR, '', $f->getPathname()) . "\n";
    }
}

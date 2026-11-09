<?php
$dir = 'c:/xampp/htdocs/AndeanExclusive/resources/views';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

foreach ($rii as $file) {
    if ($file->isDir() || $file->getExtension() !== 'php') continue;
    $content = file_get_contents($file->getPathname());
    if (strpos($content, 'public-tour-card') !== false) {
        $rel = str_replace('\\', '/', substr($file->getPathname(), strlen($dir) + 1));
        echo "=== $rel ===\n";
        preg_match_all('/@include\([\'"]layouts\.partials\.public-tour-card[\'"][^)]*\)/s', $content, $matches);
        foreach ($matches[0] as $m) {
            echo $m . "\n\n";
        }
    }
}

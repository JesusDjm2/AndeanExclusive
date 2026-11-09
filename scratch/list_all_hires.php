<?php
$publicDir = 'c:/xampp/htdocs/AndeanExclusive/public/img';

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($publicDir));
$images = [];
foreach ($it as $f) {
    if ($f->isFile()) {
        $ext = strtolower($f->getExtension());
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $info = @getimagesize($f->getPathname());
            if ($info && $info[0] >= 1200) {
                $rel = str_replace('c:/xampp/htdocs/AndeanExclusive/public/', '', $f->getPathname());
                $rel = str_replace('\\', '/', $rel);
                $images[] = [
                    'path' => $rel,
                    'w' => $info[0],
                    'h' => $info[1],
                    'kb' => round($f->getSize() / 1024, 1)
                ];
            }
        }
    }
}

usort($images, fn($a, $b) => $b['w'] <=> $a['w']);
echo "Total images with width >= 1200px: " . count($images) . "\n\n";
foreach ($images as $im) {
    echo sprintf("%-60s %4dx%-4d  %6.1f KB\n", $im['path'], $im['w'], $im['h'], $im['kb']);
}

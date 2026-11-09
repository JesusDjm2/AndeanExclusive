<?php
$imgDir = __DIR__ . '/../public/img';
$imgCount = 0;
if (is_dir($imgDir)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($imgDir, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $f) if ($f->isFile()) $imgCount++;
}
echo "Images count: $imgCount\n";

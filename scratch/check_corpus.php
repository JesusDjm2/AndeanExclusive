<?php
$files = scandir('c:/xampp/htdocs/AndeanExclusive/public/img/thumb');
foreach ($files as $f) {
    if (stripos($f, 'corpus') !== false) {
        echo "Found in thumb: $f\n";
    }
}
$blogFiles = scandir('c:/xampp/htdocs/AndeanExclusive/public/img/blog');
foreach ($blogFiles as $f) {
    if (stripos($f, 'corpus') !== false) {
        echo "Found in blog: $f\n";
    }
}

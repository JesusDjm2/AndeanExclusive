<?php
$files = [
    'public/img/andean_exclusive_tours_slide2.jpg',
    'public/img/climbing-peru-adventures-andean.jpg',
    'public/img/hermoso-peru.jpg',
];
$base = 'c:/xampp/htdocs/AndeanExclusive/';
foreach ($files as $f) {
    $p = $base . $f;
    if (file_exists($p)) {
        $info = getimagesize($p);
        echo $f . ': ' . $info[0] . 'x' . $info[1] . ' ' . round(filesize($p)/1024, 1) . 'KB' . PHP_EOL;
    } else {
        echo $f . ': NOT FOUND' . PHP_EOL;
    }
}

<?php
$files = [
    "public/img/Machu-Picchu-exclusive.jpg",
    "public/img/tours/valle-sagrado.jpg",
    "public/img/tours/rainbow-mountain-vinicunca.jpg",
    "public/img/blog-hiking-peru.jpg"
];
foreach ($files as $f) {
    if (file_exists($f)) {
        $info = getimagesize($f);
        echo $f . ": " . $info[0] . "x" . $info[1] . " (" . round(filesize($f)/1024) . " KB)\n";
    } else {
        echo $f . ": NOT FOUND\n";
    }
}

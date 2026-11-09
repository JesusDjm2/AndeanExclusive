<?php
$f = 'c:/xampp/htdocs/AndeanExclusive/public/img/machupicchu.webp';
if (file_exists($f)) {
    $info = getimagesize($f);
    echo "machupicchu.webp: {$info[0]}x{$info[1]} (" . round(filesize($f)/1024) . " KB)\n";
} else {
    echo "NOT FOUND\n";
}

<?php
$files = scandir('c:/xampp/htdocs/AndeanExclusive/public/img/full');
foreach ($files as $f) {
    if (str_contains($f, 'centro') || str_contains($f, 'lima')) {
        echo "Found in full: $f\n";
    }
}
$thumbFiles = scandir('c:/xampp/htdocs/AndeanExclusive/public/img/thumb');
foreach ($thumbFiles as $f) {
    if (str_contains($f, 'centro') || str_contains($f, 'lima')) {
        echo "Found in thumb: $f\n";
    }
}

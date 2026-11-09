<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = DB::select('SHOW TABLES');
foreach ($tables as $t) {
    $arr = (array)$t;
    $name = reset($arr);
    if (str_contains($name, 'blog') || str_contains($name, 'tour') || str_contains($name, 'cat')) {
        echo "Table: $name\n";
    }
}

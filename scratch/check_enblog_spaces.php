<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('enblogs')->where('imgThumb', 'like', '%San Cristobal%')->orWhere('imgFull', 'like', '%San Cristobal%')->get();
foreach ($rows as $r) {
    echo "enblog ID {$r->id}, {$r->nombre}, imgThumb: {$r->imgThumb}\n";
}

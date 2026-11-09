<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (Schema::hasTable('blogs')) {
    $rows = DB::table('blogs')->where('nombre', 'like', '%Corpus%')->get();
    foreach ($rows as $r) {
        echo "English blog ID: {$r->id}, Name: {$r->nombre}, imgThumb: {$r->imgThumb}, imgFull: {$r->imgFull}\n";
    }
}

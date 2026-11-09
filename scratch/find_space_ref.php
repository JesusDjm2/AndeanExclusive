<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

foreach (['blogs', 'esblogs', 'tours', 'estours'] as $tbl) {
    if (Schema::hasTable($tbl)) {
        $cols = Schema::getColumnListing($tbl);
        $searchCols = array_intersect(['imgThumb', 'imgFull', 'cuerpo', 'descripcion'], $cols);
        $rows = DB::table($tbl)->where(function($q) use ($searchCols) {
            foreach ($searchCols as $c) {
                $q->orWhere($c, 'like', '%San Cristobal%');
            }
        })->get();
        if ($rows->count() > 0) {
            echo "Table $tbl has " . $rows->count() . " rows referencing 'San Cristobal':\n";
            foreach ($rows as $r) {
                echo "  ID: {$r->id}, Name: {$r->nombre}\n";
                if (isset($r->imgThumb) && str_contains($r->imgThumb, 'San Cristobal')) echo "     imgThumb: {$r->imgThumb}\n";
                if (isset($r->imgFull) && str_contains($r->imgFull, 'San Cristobal')) echo "     imgFull: {$r->imgFull}\n";
            }
        }
    }
}

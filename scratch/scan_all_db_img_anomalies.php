<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

$tables = ['tours', 'estours', 'enblogs', 'esblogs', 'categories', 'escategorias', 'categorias'];
$publicDir = 'c:/xampp/htdocs/AndeanExclusive/public/';

echo "=== SCANNING TABLES FOR IMAGES WITH SPACES OR MISSING FILES ===\n";

foreach ($tables as $tbl) {
    if (!Schema::hasTable($tbl)) continue;
    $cols = Schema::getColumnListing($tbl);
    $imgCols = array_intersect(['imgThumb', 'imgFull', 'portada', 'img', 'imagen', 'foto'], $cols);
    if (empty($imgCols)) continue;

    $rows = DB::table($tbl)->get();
    foreach ($rows as $r) {
        foreach ($imgCols as $c) {
            $val = $r->$c ?? null;
            if (empty($val)) continue;
            
            // Check spaces
            if (str_contains($val, ' ')) {
                echo "[$tbl ID {$r->id}] col '$c' has spaces: '$val'\n";
            }

            // Check if file exists locally
            $clean = ltrim($val, '/');
            // sometimes the prefix 'img/thumb/' is omitted or present
            $candidates = [
                $publicDir . $clean,
                $publicDir . 'img/' . $clean,
                $publicDir . 'img/thumb/' . $clean,
                $publicDir . 'img/tours/' . $clean,
                $publicDir . 'img/full/' . $clean,
                $publicDir . 'img/blog/' . $clean,
            ];
            $exists = false;
            foreach ($candidates as $cand) {
                if (file_exists($cand)) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                echo "[$tbl ID {$r->id}] col '$c' FILE DOES NOT EXIST: '$val'\n";
            }
        }
    }
}

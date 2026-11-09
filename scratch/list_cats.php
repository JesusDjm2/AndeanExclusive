<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- CATEGORIAS (ES) ---\n";
foreach (DB::table('categorias')->get() as $c) {
    echo "ID: {$c->id}, Slug: {$c->slug}, Nombre: {$c->nombre}\n";
}

echo "\n--- CATEGORIES (EN) ---\n";
foreach (DB::table('categories')->get() as $c) {
    echo "ID: {$c->id}, Slug: {$c->slug}, Nombre: {$c->nombre}\n";
}

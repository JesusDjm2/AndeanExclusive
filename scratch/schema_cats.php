<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "categorias columns: " . implode(', ', Schema::getColumnListing('categorias')) . "\n";
echo "escategorias columns: " . implode(', ', Schema::getColumnListing('escategorias')) . "\n";
echo "categories columns: " . implode(', ', Schema::getColumnListing('categories')) . "\n";

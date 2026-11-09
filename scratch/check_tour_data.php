<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Tour;

$sample = Tour::take(5)->get();
foreach ($sample as $t) {
    echo "ID: {$t->id} | Nombre: {$t->nombre} | Dias: {$t->dias} | Recorrido: {$t->recorrido}\n";
    echo "  Desc corta: " . substr($t->descripcionCorta, 0, 80) . "...\n";
}

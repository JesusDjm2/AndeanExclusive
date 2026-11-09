<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "--- ESBLOGS SLUGS ---\n";
foreach (DB::table('esblogs')->get(['id', 'slug', 'nombre']) as $r) {
    echo "ID {$r->id} | {$r->slug} | {$r->nombre}\n";
}

echo "\n--- ENBLOGS SLUGS ---\n";
foreach (DB::table('enblogs')->get(['id', 'slug', 'nombre']) as $r) {
    echo "ID {$r->id} | {$r->slug} | {$r->nombre}\n";
}

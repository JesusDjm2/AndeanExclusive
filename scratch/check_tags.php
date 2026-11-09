<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Enblog;
use App\Models\Esblog;

$b1 = Enblog::first();
echo "Tags for EN blog 1 ({$b1->nombre}): " . $b1->tags->pluck('nombre')->implode(', ') . "\n";

$b2 = Esblog::first();
echo "Tags for ES blog 1 ({$b2->nombre}): " . $b2->tags->pluck('nombre')->implode(', ') . "\n";

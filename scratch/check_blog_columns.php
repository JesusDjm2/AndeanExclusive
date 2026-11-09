<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;

echo "enblogs columns: " . implode(', ', Schema::getColumnListing('enblogs')) . "\n";
echo "esblogs columns: " . implode(', ', Schema::getColumnListing('esblogs')) . "\n";

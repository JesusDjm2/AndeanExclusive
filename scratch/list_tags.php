<?php
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "--- EN TAGS ---\n";
foreach (App\Models\Entag::withCount('blogs')->get() as $t) {
    echo "{$t->nombre} (slug: {$t->slug}) - {$t->blogs_count} blogs\n";
}

echo "\n--- ES TAGS ---\n";
foreach (App\Models\Estag::withCount('blogs')->get() as $t) {
    echo "{$t->nombre} (slug: {$t->slug}) - {$t->blogs_count} blogs\n";
}

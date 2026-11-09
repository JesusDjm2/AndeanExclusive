<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Enblog;
use App\Models\Esblog;

$lastBlogEn = Enblog::latest('updated_at')->first();
$blogsEn = Enblog::where('updated_at', '<', $lastBlogEn->updated_at)->latest('updated_at')->get();
echo "EN: Total = " . Enblog::count() . " | Returned by current query = " . $blogsEn->count() . "\n";

$lastBlogEs = Esblog::latest('updated_at')->first();
$blogsEs = Esblog::where('updated_at', '<', $lastBlogEs->updated_at)->latest('updated_at')->get();
echo "ES: Total = " . Esblog::count() . " | Returned by current query = " . $blogsEs->count() . "\n";

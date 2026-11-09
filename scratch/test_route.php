<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

// Test 1: Category filter
$cat = \App\Models\Categoria::first();
$req = \Illuminate\Http\Request::create('/proveedors?categoria_id=' . $cat->id, 'GET');
$res = $app->handle($req);
echo "Cat filter ({$cat->nombre}) Status: " . $res->getStatusCode() . "\n";

// Test 2: Search term
$req2 = \Illuminate\Http\Request::create('/proveedors?q=hotel', 'GET');
$res2 = $app->handle($req2);
echo "Search 'hotel' Status: " . $res2->getStatusCode() . "\n";

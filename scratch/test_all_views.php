<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

// Test 1: Movimientos index
echo "--- Testing /movimientos ---\n";
$req = \Illuminate\Http\Request::create('/movimientos', 'GET');
$res = $app->handle($req);
echo "Movimientos Status: " . $res->getStatusCode() . "\n";
if ($res->getStatusCode() !== 200) {
    echo substr($res->getContent(), 0, 500) . "\n";
}

// Test 2: Cuentas por pagar index
echo "--- Testing /cuentas-por-pagar ---\n";
$req2 = \Illuminate\Http\Request::create('/cuentas-por-pagar', 'GET');
$res2 = $app->handle($req2);
echo "Cuentas Status: " . $res2->getStatusCode() . "\n";
if ($res2->getStatusCode() !== 200) {
    echo substr($res2->getContent(), 0, 500) . "\n";
}

// Test 3: Proveedors index
echo "--- Testing /proveedors ---\n";
$req3 = \Illuminate\Http\Request::create('/proveedors', 'GET');
$res3 = $app->handle($req3);
echo "Proveedors Status: " . $res3->getStatusCode() . "\n";
if ($res3->getStatusCode() !== 200) {
    echo substr($res3->getContent(), 0, 500) . "\n";
}

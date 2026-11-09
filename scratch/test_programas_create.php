<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

// Test 1: Crear nuevo programa
echo "--- Testing /programas/create ---\n";
$req = \Illuminate\Http\Request::create('/programas/create', 'GET');
$res = $app->handle($req);
echo "Create Status: " . $res->getStatusCode() . "\n";
if ($res->getStatusCode() !== 200) {
    echo substr($res->getContent(), 0, 500) . "\n";
}

// Test 2: Editar un programa existente
$prog = \App\Models\Programa::first();
if ($prog) {
    echo "--- Testing /programas/{$prog->id}/edit ---\n";
    $req2 = \Illuminate\Http\Request::create("/programas/{$prog->id}/edit", 'GET');
    $res2 = $app->handle($req2);
    echo "Edit Status: " . $res2->getStatusCode() . "\n";
    if ($res2->getStatusCode() !== 200) {
        echo substr($res2->getContent(), 0, 500) . "\n";
    }
}

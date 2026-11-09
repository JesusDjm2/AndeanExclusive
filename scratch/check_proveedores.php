<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$total = \App\Models\Proveedor::count();
$sinCategoria = \App\Models\Proveedor::whereNull('categoria_id')->count();
$categorias = \App\Models\Categoria::all(['id', 'nombre']);
echo "Total proveedores: {$total}\n";
echo "Sin categoría: {$sinCategoria}\n";
echo "Categorías disponibles: " . $categorias->count() . "\n";
foreach ($categorias as $c) {
    $count = \App\Models\Proveedor::where('categoria_id', $c->id)->count();
    echo " - [{$c->id}] {$c->nombre}: {$count}\n";
}

// Check if any proveedor has invalid categoria_id
$huerfanos = \App\Models\Proveedor::whereNotNull('categoria_id')
    ->whereNotIn('categoria_id', \App\Models\Categoria::pluck('id'))
    ->count();
echo "Categorías huérfanas: {$huerfanos}\n";

// Try rendering or querying what the view does
$proveedors = \App\Models\Proveedor::paginate();
foreach ($proveedors as $p) {
    if (!$p->categoria) {
        echo "Proveedor {$p->id} ({$p->nombre}) TIENE CATEGORIA NULL!\n";
    }
}

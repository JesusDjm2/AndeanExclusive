<?php
require 'c:/xampp/htdocs/AndeanExclusive/vendor/autoload.php';
$app = require_once 'c:/xampp/htdocs/AndeanExclusive/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$publicDir = 'c:/xampp/htdocs/AndeanExclusive/public';

echo "=== AUDIT DATABASE IMAGES ===\n";

// 1. Tours (English)
if (Schema::hasTable('tours')) {
    $tours = DB::table('tours')->get();
    echo "Auditing " . $tours->count() . " English tours...\n";
    foreach ($tours as $t) {
        foreach (['imgThumb', 'imgFull', 'portada'] as $col) {
            if (!empty($t->$col)) {
                $ref = ltrim($t->$col, '/');
                if (!file_exists($publicDir . '/' . $ref)) {
                    echo "  [BROKEN] Tour ID {$t->id} ('{$t->nombre}'): col '$col' -> '$ref'\n";
                }
            }
        }
    }
}

// 2. Estours (Spanish)
if (Schema::hasTable('estours')) {
    $estours = DB::table('estours')->get();
    echo "Auditing " . $estours->count() . " Spanish estours...\n";
    foreach ($estours as $t) {
        foreach (['imgThumb', 'imgFull', 'portada'] as $col) {
            if (!empty($t->$col)) {
                $ref = ltrim($t->$col, '/');
                if (!file_exists($publicDir . '/' . $ref)) {
                    echo "  [BROKEN] Estour ID {$t->id} ('{$t->nombre}'): col '$col' -> '$ref'\n";
                }
            }
        }
    }
}

// 3. Categories
if (Schema::hasTable('categorias')) {
    $cats = DB::table('categorias')->get();
    echo "Auditing " . $cats->count() . " categorias...\n";
    foreach ($cats as $c) {
        foreach (['img', 'imagen', 'portada', 'foto'] as $col) {
            if (isset($c->$col) && !empty($c->$col)) {
                $ref = ltrim($c->$col, '/');
                if (!file_exists($publicDir . '/' . $ref)) {
                    echo "  [BROKEN] Categoria ID {$c->id} ('{$c->nombre}'): col '$col' -> '$ref'\n";
                }
            }
        }
    }
}

// 4. Blogs
foreach (['blogs', 'esblogs'] as $table) {
    if (Schema::hasTable($table)) {
        $blogs = DB::table($table)->get();
        echo "Auditing " . $blogs->count() . " $table...\n";
        foreach ($blogs as $b) {
            foreach (['img', 'imagen', 'imgThumb', 'imgFull'] as $col) {
                if (isset($b->$col) && !empty($b->$col)) {
                    $ref = ltrim($b->$col, '/');
                    if (!file_exists($publicDir . '/' . $ref)) {
                        echo "  [BROKEN] $table ID {$b->id} ('{$b->nombre}'): col '$col' -> '$ref'\n";
                    }
                }
            }
        }
    }
}

// 5. Look for broken images in blade templates with other patterns (like <img> tags in general)
echo "\n=== SCANNING ALL HTML <img> IN BLADE FILES ===\n";
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('c:/xampp/htdocs/AndeanExclusive/resources/views'));
$htmlBroken = [];
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $content = file_get_contents($file->getPathname());
        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $m);
        foreach ($m[1] as $src) {
            if (str_contains($src, '{{') || str_contains($src, '{!!') || str_starts_with($src, 'http://') || str_starts_with($src, 'https://') || str_starts_with($src, 'data:')) {
                continue;
            }
            $clean = ltrim($src, '/');
            if (!file_exists($publicDir . '/' . $clean)) {
                $htmlBroken[$clean][] = str_replace('c:/xampp/htdocs/AndeanExclusive/', '', $file->getPathname());
            }
        }
    }
}

foreach ($htmlBroken as $src => $blades) {
    echo "  [BROKEN HTML SRC] $src in:\n";
    foreach ($blades as $bl) echo "    - $bl\n";
}

echo "\nAudit complete.\n";

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Enblog;
use App\Models\Esblog;

function testRenderedContent($content) {
    $content = str_replace(
        ['https://andeanexclusive.com/', 'https://www.andeanexclusive.com/', 'http://andeanexclusive.com/'],
        '/',
        $content
    );
    $content = str_replace(
        '/img/blog/thumb/inti-raymi-andean-exclusive.jpg',
        '/img/blog/thumb/inti-raymi-andean-exclusive.webp',
        $content
    );
    $content = preg_replace_callback('/<img([^>]+)src=[\'"]([^\'"]+)[\'"]([^>]*)>/i', function ($matches) {
        $src = trim($matches[2]);
        return '<img src="' . $src . '" loading="lazy" decoding="async">';
    }, $content);
    return $content;
}

$missing = 0;
$checked = 0;
foreach (['EN' => Enblog::all(), 'ES' => Esblog::all()] as $lang => $blogs) {
    foreach ($blogs as $b) {
        $rendered = testRenderedContent($b->descripcion);
        preg_match_all('/<img[^>]+src=[\'"]([^\'"]+)[\'"]/i', $rendered, $m);
        foreach ($m[1] as $src) {
            $checked++;
            $local = public_path(ltrim(rawurldecode($src), '/'));
            if (!file_exists($local)) {
                $missing++;
                echo "FAIL in $lang {$b->slug}: $src -> local: $local\n";
            }
        }
    }
}

echo "Total images checked in rendered bodies: $checked\n";
echo "Total missing: $missing\n";

<?php
$dirs = [
    'app',
    'bootstrap',
    'config',
    'database/migrations',
    'database/seeders',
    'resources/views',
    'resources/sass',
    'resources/js',
    'routes',
    'public/build',
    'public/img',
];

$files = [
    'vite.config.js',
];

$allFiles = [];

foreach ($dirs as $dir) {
    $fullDir = __DIR__ . '/../' . $dir;
    if (!is_dir($fullDir)) continue;
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($fullDir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($iterator as $item) {
        if ($item->isFile()) {
            $rel = str_replace('\\', '/', substr($item->getPathname(), strlen(dirname(__DIR__)) + 1));
            // Skip backup/temp files
            if (str_ends_with($rel, '~') || str_ends_with($rel, '.tmp') || str_contains($rel, '.DS_Store')) continue;
            $allFiles[] = $rel;
        }
    }
}

foreach ($files as $file) {
    if (file_exists(__DIR__ . '/../' . $file)) {
        $allFiles[] = $file;
    }
}

echo "Total files to consider: " . count($allFiles) . "\n";
echo "Sample 10 files:\n";
foreach (array_slice($allFiles, 0, 10) as $f) {
    echo "  $f\n";
}

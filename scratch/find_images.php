<?php
$dir = "c:/xampp/htdocs/AndeanExclusive/public/img";
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$results = [];
foreach ($rii as $file) {
    if ($file->isDir()) continue;
    $ext = strtolower($file->getExtension());
    if (!in_array($ext, ["jpg", "jpeg", "png", "webp"])) continue;
    $path = $file->getPathname();
    $rel = str_replace("\\", "/", substr($path, strlen($dir) + 1));
    $info = @getimagesize($path);
    if ($info && $info[0] >= 1000) {
        $results[] = [
            "path" => "img/" . $rel,
            "w" => $info[0],
            "h" => $info[1],
            "size" => round(filesize($path) / 1024),
            "name" => strtolower($rel),
        ];
    }
}

echo "Total images >= 1000px: " . count($results) . "\n\n";

$keywords = [
    'ausangate', 'inti', 'cusco', 'ollantaytambo', 'viajar', 'viaje', 'peru',
    'gastronomia', 'comida', 'semana-santa', 'pachamama', 'pacaya', 'amazon',
    'selva', 'carmen', 'picnic', 'nazca', 'nasca', 'lima', 'corpus', 'apacheta'
];

foreach ($keywords as $kw) {
    echo "--- Matches for '$kw' ---\n";
    $count = 0;
    foreach ($results as $r) {
        if (strpos($r['name'], $kw) !== false) {
            echo "  {$r['w']}x{$r['h']} ({$r['size']} KB): {$r['path']}\n";
            $count++;
            if ($count >= 6) break;
        }
    }
    if ($count === 0) echo "  (none)\n";
}

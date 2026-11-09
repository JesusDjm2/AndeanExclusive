<?php
$baseDir = 'c:/xampp/htdocs/AndeanExclusive';
$viewsDir = $baseDir . '/resources/views';
$publicDir = $baseDir . '/public';

// Recursive scan for all blade files
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
$bladeFiles = [];
foreach ($iterator as $file) {
    if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
        $bladeFiles[] = $file->getPathname();
    }
}

echo "Scanning " . count($bladeFiles) . " blade files for image references...\n";

$broken = [];
$checked = 0;

foreach ($bladeFiles as $filePath) {
    $content = file_get_contents($filePath);

    // Match asset('img/...'), asset("img/..."), asset('images/...'), src="img/...", etc.
    preg_match_all("/(?:asset\(['\"]|src=['\"])([^'\"\?\#]+\.(?:jpg|jpeg|png|webp|svg|gif|JPG|PNG|WEBP))['\"]/i", $content, $matches);

    if (!empty($matches[1])) {
        foreach ($matches[1] as $imgRef) {
            // Normalize path
            $clean = ltrim($imgRef, '/');
            // If it's a dynamic blade expression like {{ $tour->... }} skip
            if (str_contains($clean, '$') || str_contains($clean, '{{') || str_contains($clean, '{!!')) {
                continue;
            }
            // Skip external URLs
            if (str_starts_with($clean, 'http://') || str_starts_with($clean, 'https://')) {
                continue;
            }

            $checked++;
            $targetPath = $publicDir . '/' . $clean;
            if (!file_exists($targetPath)) {
                $relBlade = str_replace($baseDir . '/', '', $filePath);
                $broken[] = [
                    'blade' => $relBlade,
                    'ref' => $clean,
                ];
            }
        }
    }
}

echo "Total static image references checked: $checked\n";
echo "Broken image references found: " . count($broken) . "\n\n";

$uniqueBroken = [];
foreach ($broken as $b) {
    $key = $b['ref'];
    if (!isset($uniqueBroken[$key])) {
        $uniqueBroken[$key] = [];
    }
    $uniqueBroken[$key][] = $b['blade'];
}

foreach ($uniqueBroken as $ref => $blades) {
    echo "BROKEN: $ref\n";
    echo "   Referenced in (" . count($blades) . " files):\n";
    foreach (array_slice($blades, 0, 5) as $bl) {
        echo "     - $bl\n";
    }
    if (count($blades) > 5) {
        echo "     - ... and " . (count($blades) - 5) . " more\n";
    }
}

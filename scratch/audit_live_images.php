<?php
$pages = [
    'https://andean.pachamamaspirit.com/',
    'https://andean.pachamamaspirit.com/turismo-Peru',
    'https://andean.pachamamaspirit.com/exclusive-tours',
    'https://andean.pachamamaspirit.com/tours-exclusivos',
    'https://andean.pachamamaspirit.com/around-peru',
    'https://andean.pachamamaspirit.com/alrededor-de-peru',
    'https://andean.pachamamaspirit.com/adventures',
    'https://andean.pachamamaspirit.com/tours-de-aventura',
    'https://andean.pachamamaspirit.com/peru-tourism-blog',
    'https://andean.pachamamaspirit.com/blog-turismo-peru',
];

$checkedUrls = [];
$broken = [];

$ctx = stream_context_create([
    'http' => [
        'timeout' => 15,
        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
    ]
]);

echo "Scanning live pages on https://andean.pachamamaspirit.com/...\n";

foreach ($pages as $pageUrl) {
    echo "Fetching $pageUrl ...\n";
    $html = @file_get_contents($pageUrl, false, $ctx);
    if (!$html) {
        echo "  [FAIL] Could not load $pageUrl\n";
        continue;
    }

    // Extract all img src
    preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m);
    $images = array_unique($m[1] ?? []);

    foreach ($images as $img) {
        if (str_starts_with($img, 'data:') || $img === '#' || empty($img)) continue;

        // Resolve absolute URL
        $fullUrl = $img;
        if (str_starts_with($img, '//')) {
            $fullUrl = 'https:' . $img;
        } elseif (str_starts_with($img, '/')) {
            $fullUrl = 'https://andean.pachamamaspirit.com' . $img;
        } elseif (!str_starts_with($img, 'http://') && !str_starts_with($img, 'https://')) {
            $fullUrl = 'https://andean.pachamamaspirit.com/' . $img;
        }

        if (isset($checkedUrls[$fullUrl])) {
            if ($checkedUrls[$fullUrl] !== 200) {
                $broken[$fullUrl][] = $pageUrl;
            }
            continue;
        }

        // Check headers
        $headers = @get_headers($fullUrl, false, $ctx);
        $code = 0;
        if ($headers && isset($headers[0])) {
            preg_match('/HTTP\/\S+\s+(\d+)/', $headers[0], $match);
            $code = isset($match[1]) ? (int)$match[1] : 0;
        }

        $checkedUrls[$fullUrl] = $code;
        if ($code !== 200 && $code !== 301 && $code !== 302 && $code !== 304) {
            echo "  [BROKEN IMG: $code] $fullUrl on $pageUrl\n";
            $broken[$fullUrl][] = $pageUrl;
        }
    }
}

echo "\n=== SUMMARY OF LIVE BROKEN IMAGES ===\n";
echo "Total distinct images checked: " . count($checkedUrls) . "\n";
echo "Total broken images: " . count($broken) . "\n";
foreach ($broken as $url => $onPages) {
    echo "URL ($checkedUrls[$url]): $url\n";
    foreach ($onPages as $p) echo "  - on: $p\n";
}

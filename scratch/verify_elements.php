<?php
$html = file_get_contents('http://127.0.0.1:8000/en/blog/Ausangate-mountain-snowy');

if (preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $m)) {
    echo "Schema JSON-LD: " . substr(trim($m[1]), 0, 150) . "..." . PHP_EOL;
}
if (preg_match('/<div class="article-reading-meta">(.*?)<\/div>/s', $html, $m)) {
    echo "Reading meta: " . preg_replace('/\s+/', ' ', trim(strip_tags($m[1]))) . PHP_EOL;
}
if (preg_match('/<div class="sidebar-concierge-card">(.*?)<\/div>/s', $html, $m)) {
    echo "Sidebar concierge: " . preg_replace('/\s+/', ' ', substr(trim(strip_tags($m[1])), 0, 100)) . "..." . PHP_EOL;
}
if (preg_match('/<div class="article-cta-box"[^>]*>(.*?)<\/div>/s', $html, $m)) {
    echo "Article CTA box: " . preg_replace('/\s+/', ' ', substr(trim(strip_tags($m[1])), 0, 100)) . "..." . PHP_EOL;
}

// Check that there is NO duplicate hero image immediately in article
if (strpos($html, '<div class="article-figure"') !== false) {
    echo "WARNING: article-figure duplicate hero image found!" . PHP_EOL;
} else {
    echo "OK: No duplicate hero image in article body." . PHP_EOL;
}

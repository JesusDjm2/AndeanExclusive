<?php
$html = file_get_contents('http://127.0.0.1:8000/peru-tourism-blog');
preg_match_all('/class="photo-tile"/', $html, $m);
echo "Photo tiles found: " . count($m[0]) . "\n";

if (preg_match('/<section class="photo-wall"[^>]*>/', $html, $m)) {
    echo "Section photo-wall: " . $m[0] . "\n";
}

// First tile preview
if (preg_match('/<a href="([^"]+)" class="photo-tile">(.*?)<\/a>/s', $html, $m)) {
    echo "First tile link: " . $m[1] . "\n";
    echo "First tile text: " . preg_replace('/\s+/', ' ', trim(strip_tags($m[2]))) . "\n";
}

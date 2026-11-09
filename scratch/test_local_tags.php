<?php
$urls = [
    'http://127.0.0.1:8000/en/tag/Magic-Places',
    'http://127.0.0.1:8000/es/tag/' . urlencode('Lugares-mágicos'),
    'http://127.0.0.1:8000/en/tag/History',
    'http://127.0.0.1:8000/es/tag/Historia'
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "$url => HTTP $code (length: " . strlen($html) . ")\n";
    
    // Check elements
    $hasCards = str_contains($html, 'tag-story-card');
    $hasConcierge = str_contains($html, 'sidebar-concierge-card');
    $hasCloud = str_contains($html, 'tag-cloud-card');
    $hasActivePill = str_contains($html, 'tag-pill--active');
    $hasBottomCta = str_contains($html, 'collection-bottom-cta');
    $hasSchema = str_contains($html, 'CollectionPage');
    
    echo "   Cards: " . ($hasCards ? "OK" : "NO") . 
         " | Concierge: " . ($hasConcierge ? "OK" : "NO") . 
         " | Cloud: " . ($hasCloud ? "OK" : "NO") . 
         " | Active Pill: " . ($hasActivePill ? "OK" : "NO") . 
         " | Bottom CTA: " . ($hasBottomCta ? "OK" : "NO") . 
         " | Schema: " . ($hasSchema ? "OK" : "NO") . "\n";
         
    // Check images
    if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m)) {
        $imgCount = count($m[1]);
        $spaceImgs = 0;
        foreach ($m[1] as $src) {
            if (preg_match('/\s+$/', $src)) $spaceImgs++;
        }
        echo "   Images: $imgCount (trailing spaces: $spaceImgs)\n";
    }
}

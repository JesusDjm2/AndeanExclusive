<?php
$urls = [
    'https://andean.pachamamaspirit.com/en/tag/Magic-Places',
    'https://andean.pachamamaspirit.com/es/tag/' . urlencode('Lugares-mágicos'),
    'https://andean.pachamamaspirit.com/en/tag/History',
    'https://andean.pachamamaspirit.com/es/tag/Historia'
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "$url => HTTP $code (length: " . strlen($html) . ")\n";
    
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
         
    if (preg_match('/href="([^"]*app-[^"]*\.css)"/', $html, $m)) {
        echo "   CSS bundle: " . $m[1] . "\n";
    }
}

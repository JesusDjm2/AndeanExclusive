<?php
$urls = [
    'http://127.0.0.1:8000/en/blog/Ausangate-mountain-snowy',
    'http://127.0.0.1:8000/es/blog/el-nevado-Ausangate-Cusco',
    'http://127.0.0.1:8000/en/tag/Magic-Places',
    'http://127.0.0.1:8000/es/tag/' . urlencode('Lugares-mágicos')
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "$url => HTTP $code (length: " . strlen($html) . ")\n";
    $hasConciergeCard = str_contains($html, 'sidebar-concierge-card');
    $hasCtaCard = str_contains($html, 'article-sidebar__cta-card');
    $hasSimilarStories = str_contains($html, 'article-sidebar__stories');
    
    echo "   Old bulky concierge: " . ($hasConciergeCard ? "YES (Warning)" : "NO (Clean)") . 
         " | Compact CTA: " . ($hasCtaCard ? "OK" : "NO") . 
         " | Similar Stories: " . ($hasSimilarStories ? "OK" : "N/A (tag page)") . "\n";
}

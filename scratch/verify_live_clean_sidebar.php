<?php
$urls = [
    'https://andean.pachamamaspirit.com/en/blog/Ausangate-mountain-snowy',
    'https://andean.pachamamaspirit.com/es/blog/el-nevado-Ausangate-Cusco',
    'https://andean.pachamamaspirit.com/en/tag/Magic-Places',
    'https://andean.pachamamaspirit.com/es/tag/' . urlencode('Lugares-mágicos')
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
    $hasBulky = str_contains($html, 'sidebar-concierge-card');
    $hasCtaCard = str_contains($html, 'article-sidebar__cta-card');
    $hasStories = str_contains($html, 'article-sidebar__stories');
    
    echo "   Old bulky concierge: " . ($hasBulky ? "YES (Warning)" : "NO (Clean)") . 
         " | Compact CTA: " . ($hasCtaCard ? "OK" : "NO") . 
         " | Similar Stories: " . ($hasStories ? "OK" : "N/A (tag page)") . "\n";
         
    if (preg_match('/href="([^"]*app-[^"]*\.css)"/', $html, $m)) {
        echo "   CSS bundle: " . $m[1] . "\n";
    }
}

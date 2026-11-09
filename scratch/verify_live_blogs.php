<?php
$urls = [
    'https://andean.pachamamaspirit.com/peru-tourism-blog',
    'https://andean.pachamamaspirit.com/blog-turismo-peru',
    'https://andean.pachamamaspirit.com/en/blog/Ausangate-mountain-snowy',
    'https://andean.pachamamaspirit.com/es/blog/el-nevado-Ausangate-Cusco'
];

$ctx = stream_context_create([
    'http' => ['timeout' => 15],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);

echo "=== Verifying Live Remote Site ===\n";
foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "\n$url => HTTP $code (length: " . strlen($html) . " bytes)\n";
    
    // Check CSS link
    if (preg_match('/href="([^"]*app-[^"]*\.css)"/', $html, $m)) {
        echo "   CSS bundle: " . $m[1] . "\n";
    }
    
    // Check Commercial & Editorial features
    $hasCta = str_contains($html, 'article-cta-box') || str_contains($html, 'collection-bottom-cta');
    $hasConcierge = str_contains($html, 'sidebar-concierge-card');
    $hasHighlights = str_contains($html, 'article-highlights');
    $hasAuthor = str_contains($html, 'article-author-card');
    $hasReadingTime = str_contains($html, 'article-reading-meta') || str_contains($html, 'journal-card__readtime');
    
    echo "   Commercial CTA: " . ($hasCta ? "OK" : "NO") . "\n";
    echo "   Concierge Widget: " . ($hasConcierge ? "OK" : "N/A (listing)") . "\n";
    echo "   Highlights Box: " . ($hasHighlights ? "OK" : "N/A (listing)") . "\n";
    echo "   Author Card: " . ($hasAuthor ? "OK" : "N/A (listing)") . "\n";
    echo "   Reading Time: " . ($hasReadingTime ? "OK" : "NO") . "\n";
    
    // Check all image src attributes for trailing spaces or broken patterns
    if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches)) {
        $imgUrls = $matches[1];
        $totalImgs = count($imgUrls);
        $trailingSpaces = 0;
        $legacyDomain = 0;
        foreach ($imgUrls as $img) {
            if (preg_match('/\s+$/', $img)) $trailingSpaces++;
            if (str_contains($img, 'andeanexclusive.com/img')) $legacyDomain++;
        }
        echo "   Total Images: $totalImgs | Trailing spaces: $trailingSpaces | Legacy domain: $legacyDomain\n";
    }
}

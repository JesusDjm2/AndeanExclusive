<?php
$urls = [
    'http://127.0.0.1:8000/en/blog/Ausangate-mountain-snowy',
    'http://127.0.0.1:8000/es/blog/el-nevado-Ausangate-Cusco'
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "$url => HTTP $code (length: " . strlen($html) . ")\n";
    $hasStickyAside = str_contains($html, '<aside class="article-sidebar">');
    $hasQuickCta = str_contains($html, 'article-sidebar__quick-cta');
    $hasBox = str_contains($html, 'article-sidebar__box');
    $hasSideCards = str_contains($html, 'article-side-card__media');
    
    echo "   Sticky Aside: " . ($hasStickyAside ? "OK" : "NO") . 
         " | Quick CTA: " . ($hasQuickCta ? "OK" : "NO") . 
         " | Boxes: " . ($hasBox ? "OK" : "NO") . 
         " | Side Cards Media: " . ($hasSideCards ? "OK" : "NO") . "\n";
}

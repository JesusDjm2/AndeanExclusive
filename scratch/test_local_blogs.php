<?php
$urls = [
    'http://127.0.0.1:8000/peru-tourism-blog',
    'http://127.0.0.1:8000/blog-turismo-peru',
    'http://127.0.0.1:8000/en/blog/Ausangate-mountain-snowy',
    'http://127.0.0.1:8000/es/blog/el-nevado-Ausangate-Cusco'
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "$url => HTTP $code, length: " . strlen($html) . PHP_EOL;

    // Check for broken images or trailing spaces in src
    if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m)) {
        $imgCount = count($m[1]);
        $spaceImgs = 0;
        foreach ($m[1] as $src) {
            if (preg_match('/\s+$/', $src)) {
                $spaceImgs++;
            }
        }
        echo "   Images: $imgCount, with trailing space: $spaceImgs" . PHP_EOL;
    }

    // Check for CTA box and concierge card
    $hasCta = str_contains($html, 'article-cta-box') || str_contains($html, 'collection-bottom-cta');
    $hasReadingTime = str_contains($html, 'article-reading-meta') || str_contains($html, 'journal-card__reading');
    echo "   Commercial CTA: " . ($hasCta ? "OK" : "MISSING") . ", Reading time: " . ($hasReadingTime ? "OK" : "MISSING") . PHP_EOL;
}

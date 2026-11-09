<?php
$blogs = [
    'EN Blog Detail' => 'https://andeanexclusivetours.com/en/blog/inti-raymi-the-festival-of-the-sun',
    'ES Blog Detail' => 'https://andeanexclusivetours.com/es/blog/inti-raymi-la-fiesta-del-sol',
];

foreach ($blogs as $label => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "[$label] (HTTP $code) - Size: " . strlen($html) . " bytes\n";
    $hasSidebar = str_contains($html, 'article-sidebar');
    $hasSimilar = str_contains($html, 'article-sidebar__similar');
    $hasTopics = str_contains($html, 'article-sidebar__topics');
    $hasCta = str_contains($html, 'article-sidebar__cta-card');
    echo "  Sidebar: " . ($hasSidebar ? 'YES' : 'NO') . "\n";
    echo "  Similar stories first: " . ($hasSimilar ? 'YES' : 'NO') . "\n";
    echo "  Topics cloud: " . ($hasTopics ? 'YES' : 'NO') . "\n";
    echo "  Compact bespoke CTA: " . ($hasCta ? 'YES' : 'NO') . "\n\n";
}

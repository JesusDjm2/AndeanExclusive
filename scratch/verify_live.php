<?php
$urls = [
    'https://andean.pachamamaspirit.com/',
    'https://andean.pachamamaspirit.com/exclusive-tours',
    'https://andean.pachamamaspirit.com/tours-exclusivos',
    'https://andean.pachamamaspirit.com/around-peru',
    'https://andean.pachamamaspirit.com/alrededor-de-peru',
    'https://andean.pachamamaspirit.com/adventures',
    'https://andean.pachamamaspirit.com/tours-de-aventura',
    'https://andean.pachamamaspirit.com/peru-tourism-blog',
    'https://andean.pachamamaspirit.com/blog-turismo-peru',
    'https://andean.pachamamaspirit.com/en/blog/Ausangate-mountain-snowy',
    'https://andean.pachamamaspirit.com/es/blog/el-nevado-Ausangate-Cusco',
    'https://andean.pachamamaspirit.com/img/responsive/Machu-Picchu-exclusive-mobile.webp',
    'https://andean.pachamamaspirit.com/img/responsive/valle-sagrado-mobile.webp',
    'https://andean.pachamamaspirit.com/img/responsive/rainbow-mountain-vinicunca-mobile.webp',
    'https://andean.pachamamaspirit.com/img/responsive/machupicchu-mobile.webp',
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $hasPicture = is_string($html) && strpos($html, '<picture>') !== false;
    $hasLazy = is_string($html) && strpos($html, 'loading="lazy"') !== false;
    echo sprintf("%-80s => HTTP %d | picture: %s | lazy: %s\n",
        $url,
        $code,
        $hasPicture ? 'YES' : 'NO',
        $hasLazy ? 'YES' : 'NO'
    );
}

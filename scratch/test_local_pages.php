<?php
$urls = [
    'http://127.0.0.1:8000/',
    'http://127.0.0.1:8000/exclusive-tours',
    'http://127.0.0.1:8000/tours-exclusivos',
    'http://127.0.0.1:8000/around-peru',
    'http://127.0.0.1:8000/alrededor-de-peru',
    'http://127.0.0.1:8000/adventures',
    'http://127.0.0.1:8000/tours-de-aventura',
    'http://127.0.0.1:8000/peru-tourism-blog',
    'http://127.0.0.1:8000/blog-turismo-peru',
    'http://127.0.0.1:8000/en/blog/Ausangate-mountain-snowy',
    'http://127.0.0.1:8000/es/blog/el-nevado-Ausangate-Cusco',
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $hasPicture = strpos($html, '<picture>') !== false;
    $hasLazy = strpos($html, 'loading="lazy"') !== false;
    echo sprintf("%-58s => Code: %d | <picture>: %s | lazy: %s\n",
        $url,
        $code,
        $hasPicture ? 'YES' : 'NO',
        $hasLazy ? 'YES' : 'NO'
    );
}

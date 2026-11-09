<?php
$urls = [
    'https://andeanexclusivetours.com/',
    'https://andeanexclusivetours.com/peru-tourism-blog',
    'https://andeanexclusivetours.com/blog-turismo-peru',
    'https://andeanexclusivetours.com/sitemap.xml',
    'https://andeanexclusivetours.com/robots.txt',
    'https://andeanexclusivetours.com/en/tours',
    'https://andeanexclusivetours.com/es/tours',
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $effective = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    echo "$url -> HTTP $code ($effective, length: " . strlen($body) . ")\n";
}

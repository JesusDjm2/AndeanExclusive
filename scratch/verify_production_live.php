<?php
$urls = [
    'Home EN' => 'https://andeanexclusivetours.com/',
    'Home ES' => 'https://andeanexclusivetours.com/turismo-Peru',
    'Sitemap XML' => 'https://andeanexclusivetours.com/sitemap.xml',
    'Robots TXT' => 'https://andeanexclusivetours.com/robots.txt',
    'EN Blog Mosaic' => 'https://andeanexclusivetours.com/peru-tourism-blog',
    'ES Blog Mosaic' => 'https://andeanexclusivetours.com/blog-turismo-peru',
    'EN Tag Magic Places' => 'https://andeanexclusivetours.com/en/tag/Magic-Places',
    'ES Tag Lugares Magicos' => 'https://andeanexclusivetours.com/es/tag/Lugares-Magicos',
    'EN Exclusive Tours' => 'https://andeanexclusivetours.com/exclusive-tours',
    'ES Tours Exclusivos' => 'https://andeanexclusivetours.com/tours-exclusivos',
    'EN Adventures' => 'https://andeanexclusivetours.com/adventures',
    'ES Aventuras' => 'https://andeanexclusivetours.com/tours-de-aventura',
    'EN About' => 'https://andeanexclusivetours.com/about',
    'ES Nosotros' => 'https://andeanexclusivetours.com/nosotros',
];

echo "=== VERIFYING PRODUCTION DOMAIN: https://andeanexclusivetours.com ===\n\n";

$allOk = true;

foreach ($urls as $label => $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $html = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);

    $status = ($code === 200) ? 'OK' : 'FAIL';
    if ($code !== 200) $allOk = false;

    echo "[$status] $label (HTTP $code) - Size: " . strlen($html) . " bytes\n";
    if ($code !== 200) {
        echo "       URL: $url -> Final: $finalUrl\n";
    }
}

echo "\n";
if ($allOk) {
    echo ">>> ALL PRODUCTION ENDPOINTS RETURNED HTTP 200 SUCCESS! <<<\n";
} else {
    echo "WARNING: SOME ENDPOINTS FAILED!\n";
}

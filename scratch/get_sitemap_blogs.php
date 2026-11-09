<?php
$xml = file_get_contents('https://andeanexclusivetours.com/sitemap.xml');
preg_match_all('/<loc>(https:\/\/andeanexclusivetours\.com\/[^<]*blog[^<]*)<\/loc>/', $xml, $matches);
echo "Blog URLs in sitemap (" . count($matches[1]) . " found):\n";
foreach (array_slice($matches[1], 0, 10) as $url) {
    echo "  $url\n";
}

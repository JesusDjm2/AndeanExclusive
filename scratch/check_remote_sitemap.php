<?php
$ch = curl_init('https://andeanexclusivetours.com/sitemap.xml');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$res = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Sitemap HTTP code: $code, length: " . strlen($res) . "\n";
if ($code === 200) {
    echo substr($res, 0, 400) . "\n";
}

$ch2 = curl_init('https://andeanexclusivetours.com/robots.txt');
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, false);
$res2 = curl_exec($ch2);
echo "\nRobots.txt:\n" . $res2 . "\n";

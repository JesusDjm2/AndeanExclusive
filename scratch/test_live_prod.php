<?php
$ch = curl_init('https://andeanexclusivetours.com');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
$html = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $code, Response length: " . strlen($html) . "\n";
if (preg_match('/<title>(.*?)<\/title>/i', $html, $m)) {
    echo "Title: " . $m[1] . "\n";
}
if (preg_match('/href="([^"]*app-[^"]*\.css)"/', $html, $m)) {
    echo "Current CSS: " . $m[1] . "\n";
}

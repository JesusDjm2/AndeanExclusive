<?php
$ch = curl_init('https://andeanexclusivetours.com/about');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
curl_close($ch);

// Search for error details in ignition or laravel error page
if (preg_match('/<title>(.*?)<\/title>/si', $res, $m)) {
    echo "Title: " . trim($m[1]) . "\n";
}
if (preg_match('/<h1[^>]*>(.*?)<\/h1>/si', $res, $m)) {
    echo "H1: " . trim(strip_tags($m[1])) . "\n";
}
if (preg_match('/class="text-red-[^>]*>(.*?)<\//si', $res, $m)) {
    echo "Error: " . trim(strip_tags($m[1])) . "\n";
}
if (preg_match('/"message":"(.*?)"/s', $res, $m)) {
    echo "Message: " . $m[1] . "\n";
}
// Print first 500 chars if not matched
if (empty($m)) {
    echo substr(strip_tags($res), 0, 500) . "\n";
}

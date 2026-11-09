<?php
$ch = curl_init('https://andeanexclusivetours.com/about');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
curl_close($ch);

// Search for any exception text
preg_match_all('/"([A-Za-z0-9_\\\\]*Exception)"/', $res, $matches);
print_r(array_unique($matches[1] ?? []));

if (preg_match('/<div class="exception-message">\s*<h3>(.*?)<\/h3>/si', $res, $m)) {
    echo "Message: " . $m[1] . "\n";
}

// Or check remote laravel.log!

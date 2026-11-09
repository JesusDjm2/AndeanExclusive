<?php
$html = file_get_contents('https://andean.pachamamaspirit.com/en/blog/Ausangate-mountain-snowy', false, stream_context_create([
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]));

preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $m);
echo "Testing " . count($m[1]) . " images on Ausangate blog...\n";

foreach (array_unique($m[1]) as $src) {
    if (str_starts_with($src, '/')) {
        $imgUrl = 'https://andean.pachamamaspirit.com' . $src;
    } elseif (!str_starts_with($src, 'http')) {
        $imgUrl = 'https://andean.pachamamaspirit.com/' . $src;
    } else {
        $imgUrl = $src;
    }
    
    $ch = curl_init($imgUrl);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "$code <= $imgUrl\n";
}

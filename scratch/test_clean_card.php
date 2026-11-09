<?php
$ctx = stream_context_create([
    'http' => ['timeout' => 10],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);

$local = @file_get_contents('http://127.0.0.1:8000/exclusive-tours', false, $ctx);
echo "LOCAL: " . (str_contains($local, 'card-tour__icon-btn') ? 'HAS EXTRA BUTTONS' : 'CLEAN: ONLY VER ITINERARIO') . "\n";

$prod = @file_get_contents('https://andean.pachamamaspirit.com/exclusive-tours', false, $ctx);
echo "PROD:  " . (str_contains($prod, 'card-tour__icon-btn') ? 'HAS EXTRA BUTTONS' : 'CLEAN: ONLY VER ITINERARIO') . "\n";

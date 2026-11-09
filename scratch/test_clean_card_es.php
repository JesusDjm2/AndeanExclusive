<?php
$ctx = stream_context_create([
    'http' => ['timeout' => 10],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);

$localEs = @file_get_contents('http://127.0.0.1:8000/tours-exclusivos', false, $ctx);
echo "LOCAL ES: " . (str_contains($localEs, 'card-tour__icon-btn') ? 'HAS EXTRA BUTTONS' : 'CLEAN: ONLY VER ITINERARIO') . "\n";

$prodEs = @file_get_contents('https://andean.pachamamaspirit.com/tours-exclusivos', false, $ctx);
echo "PROD ES:  " . (str_contains($prodEs, 'card-tour__icon-btn') ? 'HAS EXTRA BUTTONS' : 'CLEAN: ONLY VER ITINERARIO') . "\n";

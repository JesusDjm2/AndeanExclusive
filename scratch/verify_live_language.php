<?php
$pages = [
    'EN - Exclusive Tours' => 'https://andean.pachamamaspirit.com/exclusive-tours',
    'EN - Around Peru'     => 'https://andean.pachamamaspirit.com/around-peru',
    'EN - Adventures'      => 'https://andean.pachamamaspirit.com/adventures',
    'ES - Tours Exclusivos'=> 'https://andean.pachamamaspirit.com/tours-exclusivos',
    'ES - Alrededor Peru'  => 'https://andean.pachamamaspirit.com/alrededor-de-peru',
    'ES - Aventuras'       => 'https://andean.pachamamaspirit.com/tours-de-aventura',
];

$ctx = stream_context_create([
    'http' => ['timeout' => 15],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);

foreach ($pages as $label => $url) {
    $html = file_get_contents($url, false, $ctx);
    $hasViewItinerary = strpos($html, 'View itinerary') !== false;
    $hasVerItinerario = strpos($html, 'Ver itinerario') !== false;
    $hasPrivateGuide  = strpos($html, 'Private guide') !== false;
    $hasGuiaExclusivo = strpos($html, 'Guía exclusivo') !== false;
    
    echo "=== $label ===\n";
    echo "  'View itinerary': " . ($hasViewItinerary ? 'YES' : 'NO') . "\n";
    echo "  'Ver itinerario': " . ($hasVerItinerario ? 'YES' : 'NO') . "\n";
    echo "  'Private guide':  " . ($hasPrivateGuide ? 'YES' : 'NO') . "\n";
    echo "  'Guía exclusivo': " . ($hasGuiaExclusivo ? 'YES' : 'NO') . "\n\n";
}

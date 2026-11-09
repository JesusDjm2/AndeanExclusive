<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$spanishMarkers = [
    'Ver itinerario',
    'Itinerario a medida',
    'Guía exclusivo',
    'A tu medida',
    '100% Privado',
    'Calificación 5 de 5',
    'Características clave',
    'Desde US$',
    '/ persona',
    'Diseñar mi viaje',
    'Chatear con un especialista',
    'Todos los viajes',
    'Leer artículo',
    'Leer más',
    'También te puede interesar',
    'Blogs Similares',
    'Planifiquemos tu viaje',
    'Cuéntanos sobre tu viaje',
];

$url = '/en/cusco-4-days-3-nights';
$request = Illuminate\Http\Request::create($url, 'GET');
$response = $kernel->handle($request);
$status = $response->getStatusCode();
$content = $response->getContent();

$found = [];
foreach ($spanishMarkers as $marker) {
    if (stripos($content, $marker) !== false) {
        $found[] = $marker;
    }
}

$hasViewItinerary = stripos($content, 'View itinerary') !== false;

echo sprintf("%-35s => HTTP %d | Leaks: %-25s | 'View itinerary': %s\n",
    $url,
    $status,
    empty($found) ? 'NONE (CLEAN)' : implode(', ', $found),
    $hasViewItinerary ? 'YES' : 'N/A'
);

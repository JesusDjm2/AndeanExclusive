<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$spanishUrls = [
    '/tours-exclusivos',
    '/alrededor-de-peru',
    '/tours-de-aventura',
    '/categoria/tours-exclusivos',
    '/blog-turismo-peru',
    '/es/blog/el-nevado-Ausangate-Cusco',
];

$englishMarkers = [
    'View itinerary',
    'Tailor-made journey',
    'Private guide',
    'Bespoke pace',
    '100% Private',
    'Rated 5 out of 5',
    'Key features',
    'From US$',
    '/ person',
    'Design my bespoke trip',
    'All journeys',
    'Read article',
    'Read more',
    'Similar Blogs',
];

echo "=== CHECKING SPANISH PAGES FOR ENGLISH LEAKS ===\n";

foreach ($spanishUrls as $url) {
    $request = Illuminate\Http\Request::create($url, 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();
    
    $found = [];
    foreach ($englishMarkers as $marker) {
        if (stripos($content, $marker) !== false) {
            $found[] = $marker;
        }
    }
    
    $hasVerItinerario = stripos($content, 'Ver itinerario') !== false;
    
    echo sprintf("%-35s => HTTP %d | Leaks: %-25s | 'Ver itinerario': %s\n",
        $url,
        $status,
        empty($found) ? 'NONE (CLEAN)' : implode(', ', $found),
        $hasVerItinerario ? 'YES' : 'N/A'
    );
    
    $kernel->terminate($request, $response);
}

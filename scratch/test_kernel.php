<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$uris = [
    '/',
    '/exclusive-tours',
    '/tours-exclusivos',
    '/around-peru',
    '/alrededor-de-peru',
    '/adventures',
    '/tours-de-aventura',
    '/peru-tourism-blog',
    '/blog-turismo-peru',
    '/en/blog/Ausangate-mountain-snowy',
    '/es/blog/el-nevado-Ausangate-Cusco',
];

foreach ($uris as $uri) {
    $request = Illuminate\Http\Request::create($uri, 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $content = $response->getContent();
    $hasPicture = strpos($content, '<picture>') !== false;
    $hasLazy = strpos($content, 'loading="lazy"') !== false;
    echo sprintf("%-40s => HTTP %d | picture: %s | lazy: %s\n",
        $uri,
        $status,
        $hasPicture ? 'YES' : 'NO',
        $hasLazy ? 'YES' : 'NO'
    );
    $kernel->terminate($request, $response);
}

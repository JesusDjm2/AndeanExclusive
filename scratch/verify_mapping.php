<?php
$mapping = [
    'img/full/llama-ausangate.webp',
    'img/blog/inti-raymi-andean-exclusive.jpg',
    'img/blog/INTI-RAYMI-FESTIVITY-exclusive.jpg',
    'img/full/calles-del-cusco-full.jpg',
    'img/galeria/ollantaytambo-1.webp',
    'img/blog/OLLANTAYTAMBO.webp',
    'img/blog/Como-viajar-a-Peru-Andean-Exclusive.webp',
    'img/Fondos/Recorrido-Cusco-Machu-Picchu.webp',
    'img/blog/chiriuchu-andean-exclusive.jpg',
    'img/full/gastronomia-peruana-Andean-Exclusive.webp',
    'img/blog/semana-santa-cusco.webp',
    'img/full/semana-santa-full.jpg',
    'img/blog/pachamama-ceremony-andean-exclusive.jpg',
    'img/Fondos/salkantay-valle-nevado.webp',
    'img/full/Pacaya-Samiria-Peru.webp',
    'img/blog/virgen-del-carmen-andean.jpg',
    'img/blog/virgen-del-carmen-andean-exclusive.jpg',
    'img/blog/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.webp',
    'img/Fondos/Luxury-picnic-lunch-in-Cusco-Andean-Exclusive-Tours-Travel-Agency.jpg',
    'img/full/nazca-lineas-fondo4_11zon.webp',
    'img/blog/lima-plaza-de-armas.jpg',
    'img/full/corpus-cristi.jpg',
    'img/blog/Corpus-Christi-Cusco-ESCLUSIVE.jpg',
    'img/full/apacheta-full.jpg',
    'img/galeria/apacheta-1_11zon.webp',
];

$base = 'c:/xampp/htdocs/AndeanExclusive/public/';
$allOk = true;
foreach (array_unique($mapping) as $rel) {
    $full = $base . $rel;
    if (!file_exists($full)) {
        echo "FAIL: $rel does NOT exist!\n";
        $allOk = false;
    } else {
        $info = getimagesize($full);
        $w = $info ? $info[0] : 0;
        $h = $info ? $info[1] : 0;
        $kb = round(filesize($full) / 1024);
        echo "OK: {$w}x{$h} ({$kb} KB) - $rel\n";
    }
}
if ($allOk) {
    echo "\nALL 25 HIGH-RES IMAGES EXIST AND ARE READY!\n";
}

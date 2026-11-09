<?php
function createMobileVariant($sourcePath, $destPath, $maxWidth = 768, $quality = 82) {
    if (!file_exists($sourcePath)) {
        echo "Source not found: $sourcePath\n";
        return false;
    }
    $info = getimagesize($sourcePath);
    if (!$info) {
        echo "Invalid image: $sourcePath\n";
        return false;
    }
    $origW = $info[0];
    $origH = $info[1];
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $srcImg = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $srcImg = imagecreatefrompng($sourcePath);
            break;
        case 'image/webp':
            $srcImg = imagecreatefromwebp($sourcePath);
            break;
        default:
            echo "Unsupported mime: $mime\n";
            return false;
    }

    $newW = min($maxWidth, $origW);
    $newH = round($origH * ($newW / $origW));

    $dstImg = imagecreatetruecolor($newW, $newH);
    imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

    $destDir = dirname($destPath);
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }

    imagewebp($dstImg, $destPath, $quality);
    imagedestroy($srcImg);
    imagedestroy($dstImg);

    $oldKb = round(filesize($sourcePath) / 1024);
    $newKb = round(filesize($destPath) / 1024);
    echo "Generated $destPath ($newW x $newH): $oldKb KB -> $newKb KB (" . round((1 - $newKb/$oldKb)*100) . "% savings)\n";
    return true;
}

$base = 'c:/xampp/htdocs/AndeanExclusive/public/';
createMobileVariant($base . 'img/Machu-Picchu-exclusive.jpg', $base . 'img/responsive/Machu-Picchu-exclusive-mobile.webp', 800, 82);
createMobileVariant($base . 'img/tours/valle-sagrado.jpg', $base . 'img/responsive/valle-sagrado-mobile.webp', 800, 82);
createMobileVariant($base . 'img/tours/rainbow-mountain-vinicunca.jpg', $base . 'img/responsive/rainbow-mountain-vinicunca-mobile.webp', 800, 82);
createMobileVariant($base . 'img/blog-hiking-peru.jpg', $base . 'img/responsive/blog-hiking-peru-mobile.webp', 800, 82);

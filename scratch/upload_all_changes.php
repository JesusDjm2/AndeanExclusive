<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';
$baseDir    = 'c:/xampp/htdocs/AndeanExclusive';

function ensureRemoteDir($conn, $remoteDir) {
    $parts = explode('/', trim($remoteDir, '/'));
    $current = '';
    foreach ($parts as $part) {
        $current .= '/' . $part;
        if (!@ftp_chdir($conn, $current)) {
            @ftp_mkdir($conn, $current);
        }
    }
}

function uploadFileWithRetry($server, $user, $pass, $localPath, $remotePath) {
    if (!file_exists($localPath)) {
        echo "Local not found: $localPath\n";
        return false;
    }

    $retries = 3;
    while ($retries > 0) {
        $conn = @ftp_ssl_connect($server, 21, 25);
        if (!$conn || !@ftp_login($conn, $user, $pass)) {
            $retries--;
            sleep(1);
            continue;
        }

        ftp_pasv($conn, true);
        $remoteDir = dirname($remotePath);
        ensureRemoteDir($conn, $remoteDir);

        echo "Uploading " . basename($localPath) . " -> $remotePath ... ";
        $ok = @ftp_put($conn, $remotePath, $localPath, FTP_BINARY);
        @ftp_close($conn);

        if ($ok) {
            echo "OK\n";
            return true;
        } else {
            echo "Retry...\n";
            $retries--;
            sleep(1);
        }
    }
    echo "FAILED: $remotePath\n";
    return false;
}

$filesToUpload = [
    'app/Models/Concerns/HasBlogImages.php',
    'resources/sass/components/_hero.scss',
    'resources/views/blog.blade.php',
    'resources/views/blog-castellano.blade.php',
    'resources/views/admin/enblogs/show.blade.php',
    'resources/views/admin/esblogs/show.blade.php',
    'resources/views/experiences.blade.php',
    'resources/views/experiencias.blade.php',
    'resources/views/around.blade.php',
    'resources/views/alrededor-de-peru.blade.php',
    'resources/views/adventures.blade.php',
    'resources/views/caminatas-peru.blade.php',
    'resources/views/admin/tours/categorias/show.blade.php',
    'resources/views/admin/estours/categorias/show.blade.php',
    'resources/views/index.blade.php',
    'resources/views/index-castellano.blade.php',
    'resources/views/layouts/partials/public-tour-card.blade.php',
    'database/migrations/2026_11_02_000010_update_blog_high_resolution_images.php',
    'public/ae_post_deploy.php',
    'public/build/manifest.json',
    'public/build/assets/app-DKJVVua0.css',
    'public/build/assets/admin-DoOqsTXB.css',
    'public/build/assets/programa-547VcHki.css',
    'public/img/responsive/Machu-Picchu-exclusive-mobile.webp',
    'public/img/responsive/valle-sagrado-mobile.webp',
    'public/img/responsive/rainbow-mountain-vinicunca-mobile.webp',
    'public/img/responsive/blog-hiking-peru-mobile.webp',
    'public/img/responsive/Choquequirao-tour-4-days-trekking-mobile.webp',
    'public/img/responsive/chincheros-mobile.webp',
    'public/img/responsive/machupicchu-mobile.webp',
];

echo "=== STARTING FTP UPLOAD (" . count($filesToUpload) . " files) ===\n";
foreach ($filesToUpload as $relPath) {
    $local = $baseDir . '/' . $relPath;
    $remote = '/' . $relPath;
    uploadFileWithRetry($ftp_server, $ftp_user, $ftp_pass, $local, $remote);
}
echo "=== UPLOAD COMPLETE ===\n";

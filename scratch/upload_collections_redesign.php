<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';
$baseDir    = 'C:/xampp/htdocs/AndeanExclusive';

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
    '/app/Http/Controllers/EsCatController.php',
    '/resources/js/app.js',
    '/resources/js/contact-modal.js',
    '/resources/js/collection-filter.js',
    '/resources/sass/app.scss',
    '/resources/sass/components/_cards.scss',
    '/resources/sass/components/_collections.scss',
    '/resources/views/layouts/partials/collection-trust-strip.blade.php',
    '/resources/views/layouts/partials/collection-controls.blade.php',
    '/resources/views/layouts/partials/collection-bottom-cta.blade.php',
    '/resources/views/layouts/partials/public-tour-card.blade.php',
    '/resources/views/experiencias.blade.php',
    '/resources/views/experiences.blade.php',
    '/resources/views/alrededor-de-peru.blade.php',
    '/resources/views/around.blade.php',
    '/resources/views/caminatas-peru.blade.php',
    '/resources/views/adventures.blade.php',
    '/resources/views/admin/estours/categorias/show.blade.php',
    '/resources/views/admin/tours/categorias/show.blade.php',
    '/public/build/manifest.json',
    '/public/build/assets/app-BbXnH1_X.css',
    '/public/build/assets/app-DFp2jIyz.js',
];

echo "=== Uploading Tour Collections & Cards Redesign ===\n";
$success = 0;
foreach ($filesToUpload as $file) {
    $local = $baseDir . $file;
    $remote = $file;
    if (uploadFileWithRetry($ftp_server, $ftp_user, $ftp_pass, $local, $remote)) {
        $success++;
    }
}
echo "Uploaded $success / " . count($filesToUpload) . " files.\n";

// Clear remote caches
echo "\n=== Clearing remote caches ===\n";
$clearScript = "<?php
require __DIR__ . '/vendor/autoload.php';
\$app = require_once __DIR__ . '/bootstrap/app.php';
\$kernel = \$app->make(Illuminate\\Contracts\\Console\\Kernel::class);
\$kernel->call('view:clear');
echo \$kernel->output();
\$kernel->call('route:clear');
echo \$kernel->output();
";

$conn = @ftp_ssl_connect($ftp_server, 21, 25);
if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    $tempFile = tempnam(sys_get_temp_dir(), 'ae_cache');
    file_put_contents($tempFile, $clearScript);
    if (@ftp_put($conn, '/public/ae_cache_clear.php', $tempFile, FTP_BINARY)) {
        echo "Uploaded ae_cache_clear.php\n";
        @ftp_close($conn);
        unlink($tempFile);

        $ctx = stream_context_create([
            'http' => ['timeout' => 20],
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
        ]);
        $response = @file_get_contents('https://andean.pachamamaspirit.com/ae_cache_clear.php', false, $ctx);
        echo "Cache clear response:\n$response\n";

        // Remove script
        $conn2 = @ftp_ssl_connect($ftp_server, 21, 25);
        if ($conn2 && @ftp_login($conn2, $ftp_user, $ftp_pass)) {
            @ftp_delete($conn2, '/public/ae_cache_clear.php');
            @ftp_close($conn2);
        }
    }
}

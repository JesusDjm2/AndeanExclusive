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
    '/resources/views/components/programa-codigo.blade.php',
    '/public/build/manifest.json',
    '/public/build/assets/admin-P3J8ZGa1.js',
    '/public/build/assets/admin-CncYh89j.css',
];

$successCount = 0;
foreach ($filesToUpload as $relPath) {
    $local = $baseDir . $relPath;
    $remote = $relPath;
    if (uploadFileWithRetry($ftp_server, $ftp_user, $ftp_pass, $local, $remote)) {
        $successCount++;
    }
}

echo "\nUploaded $successCount of " . count($filesToUpload) . " files.\n";

// Clear remote caches
echo "Clearing remote caches...\n";
$runnerCode = <<<'PHP'
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Illuminate\Support\Facades\Artisan::call('view:clear');
echo "view:clear: " . \Illuminate\Support\Facades\Artisan::output();
\Illuminate\Support\Facades\Artisan::call('route:clear');
echo "route:clear: " . \Illuminate\Support\Facades\Artisan::output();
PHP;

$conn = @ftp_ssl_connect($ftp_server, 21, 25);
if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    $temp = fopen('php://temp', 'r+');
    fwrite($temp, $runnerCode);
    rewind($temp);
    ftp_fput($conn, 'public/ae_cache_clear.php', $temp, FTP_BINARY);
    fclose($temp);
    ftp_close($conn);

    $ctx = stream_context_create(['http' => ['timeout' => 15]]);
    $res = @file_get_contents('https://andean.pachamamaspirit.com/ae_cache_clear.php', false, $ctx);
    echo "Cache clear result:\n$res\n";

    // Clean up
    $conn = @ftp_ssl_connect($ftp_server, 21, 25);
    if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
        ftp_delete($conn, 'public/ae_cache_clear.php');
        ftp_close($conn);
    }
}

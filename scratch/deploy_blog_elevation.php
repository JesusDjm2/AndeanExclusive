<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$baseLocal = 'C:/xampp/htdocs/AndeanExclusive';

$conn = ftp_ssl_connect($ftp_server, 21, 25);
if (!$conn || !ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("FTP connection failed\n");
}
ftp_pasv($conn, true);

$filesToUpload = [
    'app/Models/Concerns/HasBlogImages.php' => '/app/Models/Concerns/HasBlogImages.php',
    'resources/sass/components/_article.scss' => '/resources/sass/components/_article.scss',
    'resources/sass/components/_blog-mosaic.scss' => '/resources/sass/components/_blog-mosaic.scss',
    'resources/views/blog.blade.php' => '/resources/views/blog.blade.php',
    'resources/views/blog-castellano.blade.php' => '/resources/views/blog-castellano.blade.php',
    'resources/views/admin/enblogs/show.blade.php' => '/resources/views/admin/enblogs/show.blade.php',
    'resources/views/admin/esblogs/show.blade.php' => '/resources/views/admin/esblogs/show.blade.php',
    'public/build/manifest.json' => '/public/build/manifest.json',
    'public/build/assets/app-Dy4lDqMY.css' => '/public/build/assets/app-Dy4lDqMY.css',
    'public/build/assets/app-DFp2jIyz.js' => '/public/build/assets/app-DFp2jIyz.js',
    'public/build/assets/programa-547VcHki.css' => '/public/build/assets/programa-547VcHki.css',
    'public/build/assets/programa-CsUXiCuY.js' => '/public/build/assets/programa-CsUXiCuY.js',
    'public/build/assets/admin-CicUmMXE.js' => '/public/build/assets/admin-CicUmMXE.js',
    'public/build/assets/admin-DoOqsTXB.css' => '/public/build/assets/admin-DoOqsTXB.css',
];

echo "=== Uploading files to remote server ===\n";
foreach ($filesToUpload as $relLocal => $remotePath) {
    $localPath = "$baseLocal/$relLocal";
    if (!file_exists($localPath)) {
        echo "[ERR] Local file missing: $localPath\n";
        continue;
    }
    
    // Ensure remote directory exists
    $remoteDir = dirname($remotePath);
    @ftp_mkdir($conn, $remoteDir);
    
    if (ftp_put($conn, $remotePath, $localPath, FTP_BINARY)) {
        echo "[OK] Uploaded: $relLocal -> $remotePath\n";
    } else {
        echo "[FAIL] Failed to upload: $relLocal\n";
    }
}

// Create and upload temporary cache clear script
$postDeployCode = <<<'PHP'
<?php
if (($_GET['token'] ?? '') !== 'ae_deploy_elevate_2026') {
    http_response_code(403);
    die('Forbidden');
}

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "view:clear OK: " . \Illuminate\Support\Facades\Artisan::output();
} catch (\Throwable $e) {
    echo "view:clear ERR: " . $e->getMessage() . "\n";
}

try {
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "cache:clear OK: " . \Illuminate\Support\Facades\Artisan::output();
} catch (\Throwable $e) {
    echo "cache:clear ERR: " . $e->getMessage() . "\n";
}
PHP;

file_put_contents("$baseLocal/public/ae_cache_clear.php", $postDeployCode);
echo "Uploading ae_cache_clear.php...\n";
ftp_put($conn, '/public/ae_cache_clear.php', "$baseLocal/public/ae_cache_clear.php", FTP_BINARY);

// Trigger cache clear via HTTPS
echo "Triggering cache clear on remote...\n";
$ctx = stream_context_create([
    'http' => ['timeout' => 30],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);
$res = @file_get_contents('https://andean.pachamamaspirit.com/ae_cache_clear.php?token=ae_deploy_elevate_2026', false, $ctx);
echo "Remote output: $res\n";

// Clean up script
echo "Cleaning up ae_cache_clear.php...\n";
@ftp_delete($conn, '/public/ae_cache_clear.php');
@unlink("$baseLocal/public/ae_cache_clear.php");

ftp_close($conn);
echo "=== Deployment Finished ===\n";

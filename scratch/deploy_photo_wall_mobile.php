<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';
$baseLocal  = 'C:/xampp/htdocs/AndeanExclusive';

function uploadWithRetry($relLocal, $remotePath) {
    global $ftp_server, $ftp_user, $ftp_pass, $baseLocal;
    $localPath = "$baseLocal/$relLocal";
    
    for ($attempt = 1; $attempt <= 3; $attempt++) {
        $conn = @ftp_ssl_connect($ftp_server, 21, 15);
        if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
            sleep(1);
            continue;
        }
        ftp_pasv($conn, true);
        
        $remoteDir = dirname($remotePath);
        @ftp_mkdir($conn, $remoteDir);
        
        $ok = @ftp_put($conn, $remotePath, $localPath, FTP_BINARY);
        @ftp_close($conn);
        
        if ($ok) {
            echo "[OK] Uploaded ($attempt): $relLocal\n";
            return true;
        }
        sleep(1);
    }
    echo "[FAIL] $relLocal\n";
    return false;
}

$files = [
    'resources/views/blog.blade.php' => '/resources/views/blog.blade.php',
    'resources/views/blog-castellano.blade.php' => '/resources/views/blog-castellano.blade.php',
    'resources/sass/components/_blog-mosaic.scss' => '/resources/sass/components/_blog-mosaic.scss',
    'public/build/manifest.json' => '/public/build/manifest.json',
    'public/build/assets/app-DhhEdOvj.css' => '/public/build/assets/app-DhhEdOvj.css',
];

echo "=== Uploading restored photo-wall with mobile visibility ===\n";
foreach ($files as $rel => $rem) {
    uploadWithRetry($rel, $rem);
}

// Clear remote cache
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
uploadWithRetry('public/ae_cache_clear.php', '/public/ae_cache_clear.php');

echo "Calling cache clear on remote...\n";
$ctx = stream_context_create([
    'http' => ['timeout' => 30],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);
$res = @file_get_contents('https://andean.pachamamaspirit.com/ae_cache_clear.php?token=ae_deploy_elevate_2026', false, $ctx);
echo "Remote output: $res\n";

// Delete remote script
$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    @ftp_delete($conn, '/public/ae_cache_clear.php');
    @ftp_close($conn);
    echo "Deleted remote ae_cache_clear.php\n";
}
@unlink("$baseLocal/public/ae_cache_clear.php");
echo "Done!\n";

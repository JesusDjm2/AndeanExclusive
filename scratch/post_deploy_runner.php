<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$runnerCode = <<<'PHP'
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(180);

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain; charset=utf-8');
echo "=== PRODUCTION POST-DEPLOY EXECUTION ===\n\n";

try {
    echo "1. Running migrations (migrate --force)...\n";
    Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo Illuminate\Support\Facades\Artisan::output() . "\n";

    echo "2. Clearing view cache...\n";
    Illuminate\Support\Facades\Artisan::call('view:clear');
    echo Illuminate\Support\Facades\Artisan::output() . "\n";

    echo "3. Clearing application cache...\n";
    Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo Illuminate\Support\Facades\Artisan::output() . "\n";

    echo "4. Clearing route cache...\n";
    Illuminate\Support\Facades\Artisan::call('route:clear');
    echo Illuminate\Support\Facades\Artisan::output() . "\n";

    echo "5. Clearing config cache...\n";
    Illuminate\Support\Facades\Artisan::call('config:clear');
    echo Illuminate\Support\Facades\Artisan::output() . "\n";

    echo "6. Checking Spatie Roles / Tables...\n";
    $hasRoles = Illuminate\Support\Facades\Schema::hasTable('roles');
    $hasPerms = Illuminate\Support\Facades\Schema::hasTable('permissions');
    $hasProgramas = Illuminate\Support\Facades\Schema::hasTable('programas');
    $hasConsultas = Illuminate\Support\Facades\Schema::hasTable('consultas');
    echo "Roles table: " . ($hasRoles ? 'OK' : 'MISSING') . "\n";
    echo "Permissions table: " . ($hasPerms ? 'OK' : 'MISSING') . "\n";
    echo "Programas table: " . ($hasProgramas ? 'OK' : 'MISSING') . "\n";
    echo "Consultas table: " . ($hasConsultas ? 'OK' : 'MISSING') . "\n";

    echo "\n7. Testing SitemapController output...\n";
    $sitemapController = new App\Http\Controllers\SitemapController();
    $sitemapResponse = $sitemapController->index();
    echo "Sitemap status: " . $sitemapResponse->status() . ", size: " . strlen($sitemapResponse->content()) . " bytes\n";

    echo "\n=== ALL POST-DEPLOY STEPS FINISHED SUCCESSFULLY ===\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
PHP;

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("FTP Connection failed\n");
}
ftp_pasv($conn, true);

$temp = fopen('php://temp', 'r+');
fwrite($temp, $runnerCode);
rewind($temp);
$uploaded = ftp_fput($conn, 'public/_post_deploy_runner.php', $temp, FTP_BINARY);
fclose($temp);
@ftp_close($conn);

if (!$uploaded) {
    die("Failed to upload runner script\n");
}

echo "Runner uploaded. Invoking via HTTPS...\n";

$ch = curl_init('https://andeanexclusivetours.com/_post_deploy_runner.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 90);
$output = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $code\n";
echo "Output:\n$output\n";

// Cleanup runner immediately
echo "Cleaning up runner file...\n";
$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
@ftp_delete($conn, 'public/_post_deploy_runner.php');
@ftp_close($conn);
echo "Cleanup done!\n";

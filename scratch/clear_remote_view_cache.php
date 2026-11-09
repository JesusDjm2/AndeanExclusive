<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$runnerLocal = 'C:/xampp/htdocs/AndeanExclusive/scratch/remote_clear_view.php';
$runnerContent = <<<'PHP'
<?php
if (($_GET['t'] ?? '') !== 'ae_clr_9918') { die('deny'); }
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
Illuminate\Support\Facades\Artisan::call('view:clear');
echo "VIEW_CLEARED";
PHP;
file_put_contents($runnerLocal, $runnerContent);

$conn = ftp_ssl_connect($ftp_server, 21, 25);
if ($conn && ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    ftp_put($conn, '/public/remote_clear_view.php', $runnerLocal, FTP_BINARY);
    ftp_close($conn);
}

$ctx = stream_context_create([
    'http' => ['timeout' => 10],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);
$res = @file_get_contents('https://andean.pachamamaspirit.com/remote_clear_view.php?t=ae_clr_9918', false, $ctx);
echo "Remote response: $res\n";

// Cleanup
$conn = ftp_ssl_connect($ftp_server, 21, 25);
if ($conn && ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    @ftp_delete($conn, '/public/remote_clear_view.php');
    ftp_close($conn);
}
@unlink($runnerLocal);
echo "Cleaned up.\n";

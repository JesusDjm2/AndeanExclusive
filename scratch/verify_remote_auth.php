<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$runnerCode = <<<'PHP'
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::first();
\Illuminate\Support\Facades\Auth::login($user);

header('Content-Type: text/plain; charset=utf-8');
try {
    $req = \Illuminate\Http\Request::create('/proveedors', 'GET');
    $res = $app->handle($req);
    echo "Proveedors Status: " . $res->getStatusCode() . "\n";

    $req2 = \Illuminate\Http\Request::create('/proveedors?categoria_id=1', 'GET');
    $res2 = $app->handle($req2);
    echo "Proveedors with Cat 1 Status: " . $res2->getStatusCode() . "\n";

    $req3 = \Illuminate\Http\Request::create('/movimientos', 'GET');
    $res3 = $app->handle($req3);
    echo "Movimientos Status: " . $res3->getStatusCode() . "\n";

    $req4 = \Illuminate\Http\Request::create('/cuentas-por-pagar', 'GET');
    $res4 = $app->handle($req4);
    echo "Cuentas Status: " . $res4->getStatusCode() . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
}
PHP;

$conn = @ftp_ssl_connect($ftp_server, 21, 20);
if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    $temp = fopen('php://temp', 'r+');
    fwrite($temp, $runnerCode);
    rewind($temp);
    ftp_fput($conn, 'public/ae_verify.php', $temp, FTP_BINARY);
    fclose($temp);
    @ftp_close($conn);

    $ctx = stream_context_create(['http' => ['timeout' => 15]]);
    $out = @file_get_contents('https://andean.pachamamaspirit.com/ae_verify.php', false, $ctx);
    echo "VERIFICATION OUTPUT:\n" . $out . "\n";

    $conn = @ftp_ssl_connect($ftp_server, 21, 20);
    if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
        @ftp_delete($conn, 'public/ae_verify.php');
        @ftp_close($conn);
    }
}

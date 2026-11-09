<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$code = <<<'PHP'
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = App\Models\Programa::whereNotNull('token')->first();
if ($p) {
    echo "PROD_TOKEN:" . $p->token . "\n";
    echo "PROD_NAME:" . $p->nombre . "\n";
} else {
    echo "NO_TOKEN_IN_PROD\n";
}
PHP;

$conn = @ftp_ssl_connect($ftp_server, 21, 25);
if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    $temp = fopen('php://temp', 'r+');
    fwrite($temp, $code);
    rewind($temp);
    ftp_fput($conn, 'public/check_prod_token.php', $temp, FTP_BINARY);
    fclose($temp);
    ftp_close($conn);

    $ctx = stream_context_create(['http' => ['timeout' => 15]]);
    $res = @file_get_contents('https://andean.pachamamaspirit.com/check_prod_token.php', false, $ctx);
    echo "Result:\n$res\n";

    // Clean up
    $conn = @ftp_ssl_connect($ftp_server, 21, 25);
    if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
        ftp_delete($conn, 'public/check_prod_token.php');
        ftp_close($conn);
    }
}

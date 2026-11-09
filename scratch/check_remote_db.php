<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("Connection failed\n");
}
ftp_pasv($conn, true);

$code = <<<'PHP'
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

header('Content-Type: text/plain');

try {
    $tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
    echo "=== TABLES IN REMOTE DB ===\n";
    foreach ($tables as $t) {
        $name = array_values((array)$t)[0];
        echo "- $name\n";
    }

    echo "\n=== MIGRATIONS RAN ===\n";
    if (Illuminate\Support\Facades\Schema::hasTable('migrations')) {
        $migs = Illuminate\Support\Facades\DB::table('migrations')->pluck('migration');
        foreach ($migs as $m) {
            echo "  $m\n";
        }
    } else {
        echo "No migrations table!\n";
    }
} catch (Throwable $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}
PHP;

$temp = fopen('php://temp', 'r+');
fwrite($temp, $code);
rewind($temp);
ftp_fput($conn, 'public/_db_check.php', $temp, FTP_BINARY);
fclose($temp);
@ftp_close($conn);

$res = file_get_contents('https://andeanexclusivetours.com/_db_check.php');
echo $res;

// Delete immediately
$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
@ftp_delete($conn, 'public/_db_check.php');
@ftp_close($conn);

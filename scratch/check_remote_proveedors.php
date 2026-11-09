<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 20);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    echo "FTP login failed\n";
    exit(1);
}
ftp_pasv($conn, true);

// Create runner content
$runnerCode = <<<'PHP'
<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

try {
    $proveedors = \App\Models\Proveedor::with('categoria')->paginate();
    echo "Total proveedores: " . $proveedors->total() . "\n";
    foreach ($proveedors as $p) {
        $cat = $p->categoria ? $p->categoria->nombre : 'NULL';
        if ($cat === 'NULL') {
            echo "Proveedor ID {$p->id} ({$p->nombre}) has NO categoria (categoria_id: " . var_export($p->categoria_id, true) . ")\n";
        }
    }

    // Try rendering view
    $html = view('proveedor.index', [
        'proveedors' => $proveedors,
        'i' => 0
    ])->render();
    echo "Render view SUCCESS! Length: " . strlen($html) . "\n";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
PHP;

$temp = fopen('php://temp', 'r+');
fwrite($temp, $runnerCode);
rewind($temp);

ftp_fput($conn, 'public/ae_check_prov.php', $temp, FTP_BINARY);
fclose($temp);
ftp_close($conn);

echo "Runner uploaded. Calling URL...\n";
$ctx = stream_context_create(['http' => ['timeout' => 20]]);
$result = @file_get_contents('https://andean.pachamamaspirit.com/ae_check_prov.php', false, $ctx);
echo "Result:\n" . $result . "\n";

// Remove remote runner
$conn = @ftp_ssl_connect($ftp_server, 21, 20);
if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_delete($conn, 'public/ae_check_prov.php');
    ftp_close($conn);
    echo "Runner removed.\n";
}

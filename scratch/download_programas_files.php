<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$destBase = __DIR__ . '/remote_download';
if (!is_dir($destBase)) mkdir($destBase, 0777, true);

$files = [
    'resources/views/programas/form.blade.php',
    'resources/views/programas/partials/servicio-row.blade.php',
    'resources/views/clientes/index.blade.php',
    'resources/views/admin/partials/image-preview-modal.blade.php',
    'resources/js/image-preview.js',
    'resources/js/programa-form.js',
    'routes/web.php',
];

foreach ($files as $f) {
    $conn = @ftp_ssl_connect($ftp_server, 21, 15);
    @ftp_login($conn, $ftp_user, $ftp_pass);
    ftp_pasv($conn, true);

    $target = "$destBase/" . str_replace('/', '_', $f);
    echo "Downloading $f...\n";
    if (@ftp_get($conn, $target, $f, FTP_BINARY)) {
        echo " -> OK (" . filesize($target) . " bytes)\n";
    } else {
        echo " -> FAILED\n";
    }
    @ftp_close($conn);
}

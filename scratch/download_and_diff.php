<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 20);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

$destBase = __DIR__ . '/remote_download';
if (!is_dir($destBase)) mkdir($destBase, 0777, true);

// Key files to inspect differences
$filesToDiff = [
    'app/Http/Controllers/ClienteController.php',
    'app/Http/Controllers/TourController.php',
    'app/Http/Controllers/EnblogController.php',
    'app/Http/Controllers/EsblogController.php',
    'app/Support/AdminNavigation.php',
    'app/Support/Ayuda.php',
    'resources/js/image-preview.js',
    'resources/js/admin.js',
    'resources/js/programa-form.js',
    'resources/views/admin/partials/image-preview-modal.blade.php',
    'resources/views/clientes/index.blade.php',
    'resources/views/programas/form.blade.php',
    'resources/views/programas/partials/servicio-row.blade.php',
    'routes/web.php',
];

echo "Downloading key files to diff...\n";
foreach ($filesToDiff as $f) {
    $localTarget = "$destBase/" . str_replace('/', '_', $f);
    if (@ftp_get($conn, $localTarget, $f, FTP_BINARY)) {
        $localOriginal = __DIR__ . '/../' . $f;
        $sizeRemote = filesize($localTarget);
        $sizeLocal  = file_exists($localOriginal) ? filesize($localOriginal) : 0;
        $differs = ($sizeRemote !== $sizeLocal || (file_exists($localOriginal) && md5_file($localTarget) !== md5_file($localOriginal)));
        echo sprintf(" %-50s | Remote: %6d b | Local: %6d b | %s\n", $f, $sizeRemote, $sizeLocal, $differs ? 'DIFFERENT' : 'IDENTICAL');
    } else {
        echo " Failed to download $f\n";
    }
}

@ftp_close($conn);

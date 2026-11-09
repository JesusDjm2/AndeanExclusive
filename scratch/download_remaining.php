<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';
$stagingDir = __DIR__ . '/production_sync';

$files = [
    'app/Support/Ayuda.php',
    'database/seeders/PeruProveedoresHotelesSeeder.php',
    'database/seeders/ProgramaSeeder.php',
    'database/seeders/TarifaSeeder.php',
    'resources/js/programa-form.js',
    'resources/views/admin/tours/create.blade.php',
    'resources/views/admin/tours/edit.blade.php',
    'resources/views/admin/estours/edit.blade.php',
    'resources/views/programas/form.blade.php',
];

foreach ($files as $file) {
    echo "Processing $file...\n";
    $conn = @ftp_ssl_connect($ftp_server, 21, 30);
    if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
        echo "  Connection failed\n";
        continue;
    }
    ftp_pasv($conn, true);

    $size = ftp_size($conn, $file);
    echo "  Remote size: $size bytes\n";
    if ($size < 0) {
        // Maybe file doesn't exist or is in public_html?
        // Let's check public_html/$file or check nlist
        $parent = dirname($file);
        $list = ftp_nlist($conn, $parent);
        echo "  Files in $parent: " . implode(', ', array_slice($list ?: [], 0, 10)) . "\n";
    } else {
        $target = $stagingDir . '/' . $file;
        if (!is_dir(dirname($target))) mkdir(dirname($target), 0777, true);
        $ok = ftp_get($conn, $target, $file, FTP_BINARY);
        echo "  Download: " . ($ok ? "SUCCESS (" . filesize($target) . " bytes)" : "FAILED: " . error_get_last()['message']) . "\n";
    }
    @ftp_close($conn);
}

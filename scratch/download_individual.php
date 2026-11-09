<?php
$conn = @ftp_ssl_connect('ftp.andeanexclusivetours.com', 21, 25);
@ftp_login($conn, 'andean@andeanexclusivetours.com', 'AndeanExclusive12!');
ftp_pasv($conn, true);

$targets = [
    'resources/views/programas/form.blade.php' => __DIR__ . '/remote_download/programas_form.blade.php',
    'resources/js/programa-form.js' => __DIR__ . '/remote_download/programa_form.js',
    'routes/web.php' => __DIR__ . '/remote_download/routes_web.php',
];

foreach ($targets as $remote => $local) {
    echo "Downloading $remote...\n";
    $ok = @ftp_get($conn, $local, $remote, FTP_BINARY);
    if (!$ok) {
        $err = error_get_last();
        echo "Error: " . ($err ? $err['message'] : 'unknown') . "\n";
    } else {
        echo "Success: " . filesize($local) . " bytes\n";
    }
}

@ftp_close($conn);

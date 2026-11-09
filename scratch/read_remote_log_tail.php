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

$size = ftp_size($conn, "storage/logs/laravel.log");
echo "Remote log size: $size bytes\n";
if ($size > 0) {
    // Download using resume from $size - 25000
    $start = max(0, $size - 25000);
    $temp = fopen('php://temp', 'r+');
    if (@ftp_fget($conn, $temp, "storage/logs/laravel.log", FTP_BINARY, $start)) {
        rewind($temp);
        $content = stream_get_contents($temp);
        echo "=== LAST LOG ENTRIES ===\n";
        echo substr($content, -3000);
    }
    fclose($temp);
}
ftp_close($conn);

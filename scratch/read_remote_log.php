<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

$temp = fopen('php://temp', 'r+');
if (@ftp_fget($conn, $temp, 'storage/logs/laravel.log', FTP_ASCII)) {
    rewind($temp);
    $content = stream_get_contents($temp);
    $lines = explode("\n", $content);
    $lastLines = array_slice($lines, -50);
    echo "=== LAST 50 LINES OF REMOTE LARAVEL.LOG ===\n";
    echo implode("\n", $lastLines) . "\n";
} else {
    echo "Could not read storage/logs/laravel.log\n";
}
fclose($temp);
@ftp_close($conn);

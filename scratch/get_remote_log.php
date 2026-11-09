<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 25);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    echo "FTP login failed\n";
    exit(1);
}
ftp_pasv($conn, true);

// Get last lines of storage/logs/laravel.log
$remote_file = "storage/logs/laravel.log";
$temp = fopen('php://temp', 'r+');
if (@ftp_fget($conn, $temp, $remote_file, FTP_BINARY)) {
    rewind($temp);
    $content = stream_get_contents($temp);
    $lines = explode("\n", $content);
    $last_lines = array_slice($lines, -80);
    echo "=== LAST 80 LINES OF REMOTE LOG ===\n";
    echo implode("\n", $last_lines);
} else {
    echo "Could not read remote log\n";
}
fclose($temp);
ftp_close($conn);

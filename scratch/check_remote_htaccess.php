<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

$temp = fopen('php://temp', 'r+');
@ftp_fget($conn, $temp, '.htaccess', FTP_ASCII);
rewind($temp);
echo "=== REMOTE .htaccess ===\n" . stream_get_contents($temp) . "\n";
fclose($temp);

$temp2 = fopen('php://temp', 'r+');
@ftp_fget($conn, $temp2, 'public/.htaccess', FTP_ASCII);
rewind($temp2);
echo "=== REMOTE public/.htaccess ===\n" . stream_get_contents($temp2) . "\n";
fclose($temp2);

@ftp_close($conn);

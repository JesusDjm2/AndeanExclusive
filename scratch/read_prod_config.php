<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("FTP login failed\n");
}
ftp_pasv($conn, true);

$tempEnv = tempnam(sys_get_temp_dir(), 'env_');
$tempHtaccess = tempnam(sys_get_temp_dir(), 'ht_');

if (ftp_get($conn, $tempEnv, '/.env', FTP_BINARY)) {
    echo "=== REMOTE .ENV ===\n";
    $lines = file($tempEnv);
    foreach ($lines as $line) {
        if (preg_match('/^(APP_URL|APP_ENV|DB_|SEO_)/', $line)) {
            echo trim($line) . "\n";
        }
    }
}
@unlink($tempEnv);

if (ftp_get($conn, $tempHtaccess, '/.htaccess', FTP_BINARY)) {
    echo "\n=== REMOTE .HTACCESS ===\n";
    echo file_get_contents($tempHtaccess) . "\n";
}
@unlink($tempHtaccess);

ftp_close($conn);

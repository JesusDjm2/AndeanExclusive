<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("Connection failed\n");
}
ftp_pasv($conn, true);

$spatie = ftp_nlist($conn, 'vendor/spatie');
echo "vendor/spatie: " . ($spatie ? implode(', ', $spatie) : 'NOT FOUND') . "\n";

$vendor = ftp_nlist($conn, 'vendor');
echo "vendor packages count: " . count($vendor ?: []) . "\n";

@ftp_close($conn);

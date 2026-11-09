<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

echo "nlist('app'):\n";
print_r(ftp_nlist($conn, 'app'));

echo "nlist('routes'):\n";
print_r(ftp_nlist($conn, 'routes'));
@ftp_close($conn);

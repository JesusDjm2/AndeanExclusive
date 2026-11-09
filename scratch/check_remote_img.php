<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

$list = ftp_nlist($conn, 'public/img');
echo "Remote public/img count: " . count($list ?: []) . "\n";
echo "Sample entries:\n";
foreach (array_slice($list ?: [], 0, 15) as $item) {
    echo "  $item\n";
}
@ftp_close($conn);

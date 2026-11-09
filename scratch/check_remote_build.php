<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("Connection failed\n");
}
ftp_pasv($conn, true);

$buildList = ftp_rawlist($conn, 'public/build');
echo "public/build contents:\n";
foreach ($buildList ?: [] as $line) echo "  $line\n";

$assetList = ftp_rawlist($conn, 'public/build/assets');
echo "public/build/assets contents:\n";
foreach ($assetList ?: [] as $line) echo "  $line\n";

@ftp_close($conn);

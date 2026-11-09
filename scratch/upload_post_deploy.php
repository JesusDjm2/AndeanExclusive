<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$conn = ftp_ssl_connect($ftp_server, 21, 25);
if (!$conn || !ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("FTP connection failed\n");
}
ftp_pasv($conn, true);

$local = 'C:/xampp/htdocs/AndeanExclusive/public/ae_post_deploy.php';
$remote = '/public/ae_post_deploy.php';

echo "Uploading post-deploy script...\n";
if (ftp_put($conn, $remote, $local, FTP_BINARY)) {
    echo "Uploaded successfully.\n";
} else {
    echo "Failed to upload.\n";
}
ftp_close($conn);

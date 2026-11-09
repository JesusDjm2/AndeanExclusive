<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$conn = ftp_ssl_connect($ftp_server, 21, 25);
if ($conn && ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    @ftp_delete($conn, '/public/ae_post_deploy.php');
    echo "Removed remote ae_post_deploy.php\n";
    @ftp_close($conn);
}
@unlink('c:/xampp/htdocs/AndeanExclusive/public/ae_post_deploy.php');
echo "Removed local ae_post_deploy.php\n";

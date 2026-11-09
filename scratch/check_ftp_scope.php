<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 10);
if ($conn && @ftp_login($conn, $ftp_user, $ftp_pass)) {
    ftp_pasv($conn, true);
    echo "Current dir: " . ftp_pwd($conn) . "\n";
    $list = ftp_nlist($conn, '.');
    echo "List:\n" . implode("\n", array_slice($list, 0, 15)) . "\n";
    @ftp_cdup($conn);
    echo "After cdup: " . ftp_pwd($conn) . "\n";
    ftp_close($conn);
} else {
    echo "Login failed\n";
}

<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

function ensureRemoteDir($conn, $path) {
    $parts = explode('/', trim(str_replace('\\', '/', $path), '/'));
    $cur = '';
    foreach ($parts as $p) {
        $cur .= ($cur === '' ? '' : '/') . $p;
        @ftp_mkdir($conn, $cur);
    }
}

ensureRemoteDir($conn, 'storage/test_dir/sub');
$list = ftp_nlist($conn, 'storage/test_dir');
echo "storage/test_dir contents: " . implode(', ', $list ?: []) . "\n";

// cleanup
@ftp_rmdir($conn, 'storage/test_dir/sub');
@ftp_rmdir($conn, 'storage/test_dir');
@ftp_close($conn);

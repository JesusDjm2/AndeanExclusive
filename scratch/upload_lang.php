<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

function ensureDir($conn, $path) {
    $parts = explode('/', trim(str_replace('\\', '/', $path), '/'));
    $cur = '';
    foreach ($parts as $p) {
        $cur .= ($cur === '' ? '' : '/') . $p;
        @ftp_mkdir($conn, $cur);
    }
}

$base = dirname(__DIR__);
$langDir = $base . '/lang';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($langDir, RecursiveDirectoryIterator::SKIP_DOTS));

foreach ($it as $item) {
    if ($item->isFile()) {
        $rel = str_replace('\\', '/', substr($item->getPathname(), strlen($base) + 1));
        ensureDir($conn, dirname($rel));
        $ok = ftp_put($conn, $rel, $item->getPathname(), FTP_ASCII);
        echo "Uploaded $rel: " . ($ok ? 'OK' : 'FAILED') . "\n";
    }
}

@ftp_close($conn);

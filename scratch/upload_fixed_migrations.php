<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

$files = [
    'database/migrations/0001_01_01_000000_create_users_table.php',
    'database/migrations/0001_01_01_000001_create_cache_table.php',
    'database/migrations/0001_01_01_000002_create_jobs_table.php',
];

$base = dirname(__DIR__);
foreach ($files as $f) {
    echo "Uploading $f...\n";
    $ok = ftp_put($conn, $f, $base . '/' . $f, FTP_ASCII);
    echo $ok ? "Uploaded OK\n" : "FAILED\n";
}

@ftp_close($conn);

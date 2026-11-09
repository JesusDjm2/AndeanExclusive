<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("Connection failed\n");
}
ftp_pasv($conn, true);

$temp = fopen('php://temp', 'r+');
if (@ftp_fget($conn, $temp, '.env', FTP_ASCII)) {
    rewind($temp);
    $lines = explode("\n", stream_get_contents($temp));
    echo "=== Remote .env Configuration ===\n";
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || str_starts_with($line, '#')) continue;
        if (preg_match('/^(APP_|DB_|MAIL_|FILESYSTEM_)/', $line)) {
            $parts = explode('=', $line, 2);
            $key = $parts[0];
            $val = $parts[1] ?? '';
            if (stripos($key, 'PASSWORD') !== false || stripos($key, 'PASS') !== false || stripos($key, 'SECRET') !== false) {
                $val = '********';
            }
            echo "$key = $val\n";
        }
    }
} else {
    echo "Could not read .env\n";
}
fclose($temp);
@ftp_close($conn);

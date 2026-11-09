<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

echo "Testing connection to $ftp_server with user $ftp_user...\n";
$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn) {
    die("Connection failed to $ftp_server\n");
}

if (!@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("Login failed with $ftp_user\n");
}

ftp_pasv($conn, true);
echo "Logged in successfully!\n";
echo "Current directory: " . ftp_pwd($conn) . "\n";

$rawList = ftp_rawlist($conn, '.');
echo "Directory contents (" . count($rawList) . " items):\n";
foreach (array_slice($rawList, 0, 30) as $line) {
    echo "  $line\n";
}

@ftp_close($conn);

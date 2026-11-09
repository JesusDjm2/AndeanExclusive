<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("Connection failed\n");
}
ftp_pasv($conn, true);

$content = "<?php echo 'PONG_' . time();";
$temp = fopen('php://temp', 'r+');
fwrite($temp, $content);
rewind($temp);

if (ftp_fput($conn, 'public/test_ping.php', $temp, FTP_BINARY)) {
    echo "Uploaded test_ping.php to public/ successfully\n";
} else {
    echo "Failed to upload to public/\n";
}
fclose($temp);
@ftp_close($conn);

$res = file_get_contents('https://andeanexclusivetours.com/test_ping.php');
echo "Response from live: " . $res . "\n";

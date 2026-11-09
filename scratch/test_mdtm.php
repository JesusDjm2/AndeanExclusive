<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 15);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

$files = ['routes/web.php', 'app/Http/Controllers/HomeController.php', 'composer.json', '.env'];
foreach ($files as $f) {
    $mdtm = ftp_mdtm($conn, $f);
    echo "$f: mdtm = $mdtm (" . ($mdtm > 0 ? date('Y-m-d H:i:s', $mdtm) : 'error') . ")\n";
}

echo "\nRawlist of routes:\n";
print_r(ftp_rawlist($conn, 'routes'));

echo "\nRawlist of root:\n";
print_r(ftp_rawlist($conn, '.'));

@ftp_close($conn);

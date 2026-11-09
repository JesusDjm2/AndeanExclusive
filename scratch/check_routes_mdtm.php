<?php
$conn = @ftp_ssl_connect('ftp.andeanexclusivetours.com', 21, 10);
@ftp_login($conn, 'andean@andeanexclusivetours.com', 'AndeanExclusive12!');
ftp_pasv($conn, true);

$raw = ftp_rawlist($conn, 'routes');
foreach ($raw as $line) {
    echo $line . "\n";
    $parts = preg_split('/\s+/', $line, 9);
    if (isset($parts[8])) {
        $name = $parts[8];
        $mdtm = ftp_mdtm($conn, "routes/$name");
        echo "  -> mdtm: $mdtm (" . ($mdtm > 0 ? date('Y-m-d H:i:s', $mdtm) : 'err') . ")\n";
    }
}
@ftp_close($conn);

<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 20);
@ftp_login($conn, $ftp_user, $ftp_pass);
ftp_pasv($conn, true);

$morningCutoff = strtotime('2026-11-04 15:20:00 UTC');

function checkViews($conn, $dir, $cutoff) {
    $raw = @ftp_rawlist($conn, $dir);
    if (!$raw) return;
    foreach ($raw as $l) {
        $parts = preg_split('/\s+/', $l, 9);
        if (count($parts) < 9) continue;
        $name = $parts[8];
        if ($name === '.' || $name === '..') continue;
        $p = "$dir/$name";
        if ($parts[0][0] === 'd') {
            checkViews($conn, $p, $cutoff);
        } else {
            $m = ftp_mdtm($conn, $p);
            if ($m > $cutoff) {
                echo "[LATER] $p (" . date('Y-m-d H:i:s', $m) . ")\n";
            }
        }
    }
}

echo "Checking resources/views...\n";
checkViews($conn, 'resources/views', $morningCutoff);

echo "Checking routes...\n";
checkViews($conn, 'routes', $morningCutoff);

echo "Checking root files...\n";
$rootList = ftp_rawlist($conn, '.');
foreach ($rootList as $l) {
    $parts = preg_split('/\s+/', $l, 9);
    if (count($parts) < 9) continue;
    if ($parts[0][0] !== 'd') {
        $name = $parts[8];
        $m = ftp_mdtm($conn, $name);
        if ($m > $morningCutoff) {
            echo "[LATER] $name (" . date('Y-m-d H:i:s', $m) . ")\n";
        }
    }
}

@ftp_close($conn);

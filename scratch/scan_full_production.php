<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$conn = @ftp_ssl_connect($ftp_server, 21, 20);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("Connection failed\n");
}
ftp_pasv($conn, true);

$allFiles = [];
// Today Nov 4 00:00 UTC (or local)
// Let's check from Nov 4 00:00 UTC (timestamp 1793750400)
$cutoff = 1793750400; // 2026-11-04 00:00:00 UTC

function scanPath($conn, $relPath, $cutoff, &$allFiles) {
    $rawList = @ftp_rawlist($conn, $relPath === '' ? '.' : $relPath);
    if (!$rawList) return;

    foreach ($rawList as $line) {
        $parts = preg_split('/\s+/', $line, 9);
        if (count($parts) < 9) continue;
        
        $perms = $parts[0];
        $size  = (int)$parts[4];
        $name  = $parts[8];

        if ($name === '.' || $name === '..') continue;

        $fullPath = ($relPath === '' || $relPath === '.') ? $name : "$relPath/$name";

        // Ignore large 3rd party or cache dirs
        if (in_array($name, ['.git', 'vendor', 'node_modules', 'storage', 'AndeanExclusive', 'cache'])) {
            continue;
        }

        if ($perms[0] === 'd') {
            scanPath($conn, $fullPath, $cutoff, $allFiles);
        } else {
            $mtime = @ftp_mdtm($conn, $fullPath);
            if ($mtime >= $cutoff) {
                $allFiles[] = [
                    'path' => $fullPath,
                    'size' => $size,
                    'mtime' => $mtime,
                    'mtime_str' => date('Y-m-d H:i:s', $mtime),
                ];
                echo "Found: [{$allFiles[count($allFiles)-1]['mtime_str']}] $fullPath\n";
            }
        }
    }
}

echo "Scanning from root...\n";
scanPath($conn, '', $cutoff, $allFiles);

usort($allFiles, fn($a, $b) => $b['mtime'] <=> $a['mtime']);

echo "\n============================================\n";
echo "TOTAL: " . count($allFiles) . " files modified on Nov 4 or 5\n";
echo "============================================\n";

file_put_contents(__DIR__ . '/prod_files_today.json', json_encode($allFiles, JSON_PRETTY_PRINT));
@ftp_close($conn);

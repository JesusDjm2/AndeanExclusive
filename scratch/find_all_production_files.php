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
$cutoff = strtotime('2026-11-04 00:00:00 UTC'); // Nov 4 00:00 UTC

function scanDirRaw($conn, $dir, $cutoff, &$allFiles) {
    $rawList = @ftp_rawlist($conn, $dir);
    if (!$rawList) return;

    foreach ($rawList as $line) {
        $parts = preg_split('/\s+/', $line, 9);
        if (count($parts) < 9) continue;
        
        $perms = $parts[0];
        $size  = $parts[4];
        $name  = $parts[8];

        if ($name === '.' || $name === '..') continue;

        $path = ($dir === '.' || $dir === '') ? $name : "$dir/$name";

        // Ignore certain heavy or unchanged dirs
        if (in_array($name, ['.git', 'vendor', 'node_modules', 'storage'])) {
            continue;
        }

        $isDir = ($perms[0] === 'd');

        if ($isDir) {
            scanDirRaw($conn, $path, $cutoff, $allFiles);
        } else {
            $mtime = @ftp_mdtm($conn, $path);
            if ($mtime >= $cutoff) {
                $allFiles[] = [
                    'path' => $path,
                    'size' => (int)$size,
                    'mtime' => $mtime,
                    'mtime_str' => date('Y-m-d H:i:s', $mtime),
                    'line' => $line
                ];
            }
        }
    }
}

echo "Starting scan of production files...\n";
scanDirRaw($conn, '.', $cutoff, $allFiles);

usort($allFiles, fn($a, $b) => $b['mtime'] <=> $a['mtime']);

echo "Scan complete. Found " . count($allFiles) . " files modified since Nov 4 00:00 UTC:\n\n";

foreach ($allFiles as $f) {
    echo sprintf("[%s] %8d bytes  %s\n", $f['mtime_str'], $f['size'], $f['path']);
}

file_put_contents(__DIR__ . '/prod_files_today.json', json_encode($allFiles, JSON_PRETTY_PRINT));
@ftp_close($conn);

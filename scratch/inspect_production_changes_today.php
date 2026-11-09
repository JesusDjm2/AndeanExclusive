<?php
/**
 * Scan production FTP for files modified today / recently
 */
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

echo "Connecting to $ftp_server...\n";
$conn = @ftp_ssl_connect($ftp_server, 21, 15);
if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
    die("FTP connection failed.\n");
}
ftp_pasv($conn, true);
echo "Connected successfully.\n";

$dirsToScan = [
    'app',
    'resources/views',
    'resources/js',
    'resources/sass',
    'routes',
    'database/migrations',
    'config',
    'public',
];

$cutoffTime = strtotime('2026-11-04 00:00:00'); // Today (Nov 4)
$recentFiles = [];

function scanRemoteDir($conn, $dir, $cutoffTime, &$recentFiles) {
    $items = @ftp_nlist($conn, $dir);
    if (!$items) return;

    foreach ($items as $item) {
        $basename = basename($item);
        if ($basename === '.' || $basename === '..' || $basename === '.git') continue;
        
        // Skip large media directories in public
        if ($dir === 'public' && in_array($basename, ['img', 'storage', 'fonts', 'vendor', 'admin'])) {
            continue;
        }

        // Check if item is directory
        $mdtm = @ftp_mdtm($conn, $item);
        if ($mdtm === -1) {
            // Likely a directory, recurse
            scanRemoteDir($conn, $item, $cutoffTime, $recentFiles);
        } else {
            // It's a file
            if ($mdtm >= $cutoffTime) {
                $recentFiles[] = [
                    'path' => $item,
                    'mtime' => $mdtm,
                    'mtime_str' => date('Y-m-d H:i:s', $mdtm),
                    'size' => @ftp_size($conn, $item)
                ];
            }
        }
    }
}

foreach ($dirsToScan as $d) {
    echo "Scanning $d...\n";
    scanRemoteDir($conn, $d, $cutoffTime, $recentFiles);
}

// Sort by mtime descending
usort($recentFiles, fn($a, $b) => $b['mtime'] <=> $a['mtime']);

echo "\nFound " . count($recentFiles) . " files modified since " . date('Y-m-d H:i:s', $cutoffTime) . ":\n";
foreach ($recentFiles as $f) {
    echo "  [{$f['mtime_str']}] ({$f['size']} bytes) {$f['path']}\n";
}

file_put_contents(__DIR__ . '/recent_production_files.json', json_encode($recentFiles, JSON_PRETTY_PRINT));
@ftp_close($conn);

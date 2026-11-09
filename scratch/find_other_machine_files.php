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

// Our morning deployment finished at 15:15:00 UTC (10:15 Lima time).
// Anything with mtime > 15:20:00 UTC was uploaded later from the user's other machine.
$morningCutoff = strtotime('2026-11-04 15:20:00 UTC');

$uploadedLater = [];
$uploadedMorning = [];

$scanRoots = [
    'app',
    'config',
    'database',
    'lang',
    'resources',
    'routes',
    'public/build',
    '.' // root files
];

function scanSubPath($conn, $relPath, $morningCutoff, &$uploadedLater, &$uploadedMorning) {
    $rawList = @ftp_rawlist($conn, $relPath === '.' ? '.' : $relPath);
    if (!$rawList) return;

    foreach ($rawList as $line) {
        $parts = preg_split('/\s+/', $line, 9);
        if (count($parts) < 9) continue;
        
        $perms = $parts[0];
        $size  = (int)$parts[4];
        $name  = $parts[8];

        if ($name === '.' || $name === '..') continue;

        $fullPath = ($relPath === '.' || $relPath === '') ? $name : "$relPath/$name";

        // Ignore heavy / vendor dirs
        if (in_array($name, ['.git', 'vendor', 'node_modules', 'storage', 'AndeanExclusive', 'img', 'admin', 'fonts', 'cache'])) {
            continue;
        }

        if ($perms[0] === 'd') {
            scanSubPath($conn, $fullPath, $morningCutoff, $uploadedLater, $uploadedMorning);
        } else {
            $mtime = @ftp_mdtm($conn, $fullPath);
            if ($mtime > $morningCutoff) {
                $uploadedLater[] = [
                    'path' => $fullPath,
                    'size' => $size,
                    'mtime' => $mtime,
                    'mtime_str' => date('Y-m-d H:i:s', $mtime),
                ];
                echo " [LATER TODAY] {$fullPath} ({$size} bytes, " . date('Y-m-d H:i:s', $mtime) . ")\n";
            }
        }
    }
}

echo "Scanning production code for files uploaded AFTER our morning deploy...\n";
foreach ($scanRoots as $root) {
    if ($root === '.') {
        $raw = @ftp_rawlist($conn, '.');
        foreach ($raw as $line) {
            $parts = preg_split('/\s+/', $line, 9);
            if (count($parts) < 9) continue;
            if ($parts[0][0] !== 'd') {
                $name = $parts[8];
                $mtime = @ftp_mdtm($conn, $name);
                if ($mtime > $morningCutoff) {
                    $uploadedLater[] = [
                        'path' => $name,
                        'size' => (int)$parts[4],
                        'mtime' => $mtime,
                        'mtime_str' => date('Y-m-d H:i:s', $mtime),
                    ];
                    echo " [LATER TODAY] {$name} (" . date('Y-m-d H:i:s', $mtime) . ")\n";
                }
            }
        }
    } else {
        scanSubPath($conn, $root, $morningCutoff, $uploadedLater, $uploadedMorning);
    }
}

usort($uploadedLater, fn($a, $b) => $b['mtime'] <=> $a['mtime']);

echo "\n============================================\n";
echo "TOTAL FILES UPLOADED FROM OTHER MACHINE: " . count($uploadedLater) . "\n";
echo "============================================\n";

file_put_contents(__DIR__ . '/files_uploaded_from_other_machine.json', json_encode($uploadedLater, JSON_PRETTY_PRINT));
@ftp_close($conn);

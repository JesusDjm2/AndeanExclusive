<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';
$baseDir    = 'c:/xampp/htdocs/AndeanExclusive';

function ensureRemoteDir($conn, $remoteDir) {
    $parts = explode('/', trim($remoteDir, '/'));
    $current = '';
    foreach ($parts as $part) {
        $current .= '/' . $part;
        if (!@ftp_chdir($conn, $current)) {
            @ftp_mkdir($conn, $current);
        }
    }
}

function uploadFileWithRetry($server, $user, $pass, $localPath, $remotePath) {
    if (!file_exists($localPath)) {
        echo "Local not found: $localPath\n";
        return false;
    }

    $retries = 3;
    while ($retries > 0) {
        $conn = @ftp_ssl_connect($server, 21, 25);
        if (!$conn || !@ftp_login($conn, $user, $pass)) {
            $retries--;
            sleep(1);
            continue;
        }

        ftp_pasv($conn, true);
        $remoteDir = dirname($remotePath);
        ensureRemoteDir($conn, $remoteDir);

        echo "Uploading " . basename($localPath) . " -> $remotePath ... ";
        $ok = @ftp_put($conn, $remotePath, $localPath, FTP_BINARY);
        @ftp_close($conn);

        if ($ok) {
            echo "OK\n";
            return true;
        } else {
            echo "Retry...\n";
            $retries--;
            sleep(1);
        }
    }
    echo "FAILED: $remotePath\n";
    return false;
}

$filesToUpload = [
    'resources/views/layouts/partials/public-tour-card.blade.php',
    'resources/views/layouts/partials/related-tours.blade.php',
];

echo "=== UPLOADING LANGUAGE FIX ===\n";
foreach ($filesToUpload as $relPath) {
    $local = $baseDir . '/' . $relPath;
    $remote = '/' . $relPath;
    uploadFileWithRetry($ftp_server, $ftp_user, $ftp_pass, $local, $remote);
}
echo "=== UPLOAD COMPLETE ===\n";

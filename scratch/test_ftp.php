<?php
$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andeanpacha@andean.pachamamaspirit.com';
$ftp_pass   = 'AndeanPacha12!';

function uploadWithRetry($relLocal, $remotePath) {
    global $ftp_server, $ftp_user, $ftp_pass;
    $baseLocal = 'C:/xampp/htdocs/AndeanExclusive';
    $localPath = "$baseLocal/$relLocal";
    
    for ($attempt = 1; $attempt <= 3; $attempt++) {
        $conn = @ftp_ssl_connect($ftp_server, 21, 15);
        if (!$conn || !@ftp_login($conn, $ftp_user, $ftp_pass)) {
            echo "Attempt $attempt: login failed\n";
            sleep(1);
            continue;
        }
        ftp_pasv($conn, true);
        
        $remoteDir = dirname($remotePath);
        @ftp_mkdir($conn, $remoteDir);
        
        $ok = @ftp_put($conn, $remotePath, $localPath, FTP_BINARY);
        @ftp_close($conn);
        
        if ($ok) {
            echo "[OK] Uploaded ($attempt): $relLocal\n";
            return true;
        }
        echo "Attempt $attempt: upload failed for $relLocal\n";
        sleep(1);
    }
    echo "[FAIL] $relLocal\n";
    return false;
}

$assets = [
    'public/build/manifest.json' => '/public/build/manifest.json',
    'public/build/assets/app-Dy4lDqMY.css' => '/public/build/assets/app-Dy4lDqMY.css',
    'public/build/assets/app-DFp2jIyz.js' => '/public/build/assets/app-DFp2jIyz.js',
    'public/build/assets/programa-547VcHki.css' => '/public/build/assets/programa-547VcHki.css',
    'public/build/assets/programa-CsUXiCuY.js' => '/public/build/assets/programa-CsUXiCuY.js',
    'public/build/assets/admin-CicUmMXE.js' => '/public/build/assets/admin-CicUmMXE.js',
    'public/build/assets/admin-DoOqsTXB.css' => '/public/build/assets/admin-DoOqsTXB.css',
];

foreach ($assets as $rel => $rem) {
    uploadWithRetry($rel, $rem);
}

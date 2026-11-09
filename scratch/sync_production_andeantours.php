<?php
/**
 * Andean Exclusive - Production Synchronization to andeanexclusivetours.com
 */

set_time_limit(0);
ini_set('memory_limit', '512M');

$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

class FtpSync {
    private $server;
    private $user;
    private $pass;
    private $conn = null;
    private $knownDirs = [];
    private $transfersSinceConnect = 0;

    public function __construct($server, $user, $pass) {
        $this->server = $server;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function connect() {
        if ($this->conn) {
            @ftp_close($this->conn);
            $this->conn = null;
        }
        $conn = @ftp_ssl_connect($this->server, 21, 20);
        if (!$conn || !@ftp_login($conn, $this->user, $this->pass)) {
            throw new Exception("FTP Login failed for {$this->user}@{$this->server}");
        }
        ftp_pasv($conn, true);
        $this->conn = $conn;
        $this->transfersSinceConnect = 0;
        return $this->conn;
    }

    public function ensureConnected() {
        if (!$this->conn || $this->transfersSinceConnect >= 40) {
            $this->connect();
        }
        return $this->conn;
    }

    public function ensureRemoteDir($remoteDir) {
        $remoteDir = trim(str_replace('\\', '/', $remoteDir), '/');
        if (empty($remoteDir) || isset($this->knownDirs[$remoteDir])) {
            return;
        }

        $parts = explode('/', $remoteDir);
        $accum = '';
        foreach ($parts as $p) {
            $accum .= ($accum === '' ? '' : '/') . $p;
            if (!isset($this->knownDirs[$accum])) {
                @ftp_mkdir($this->conn, $accum);
                $this->knownDirs[$accum] = true;
            }
        }
    }

    public function uploadFile($localPath, $remotePath, $force = false) {
        $localPath = str_replace('\\', '/', $localPath);
        $remotePath = str_replace('\\', '/', $remotePath);

        if (!file_exists($localPath)) {
            return false;
        }

        $localSize = filesize($localPath);

        $maxAttempts = 3;
        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                $conn = $this->ensureConnected();

                $remoteDir = dirname($remotePath);
                if ($remoteDir !== '.' && $remoteDir !== '') {
                    $this->ensureRemoteDir($remoteDir);
                }

                // If not forced, check if remote size matches
                if (!$force) {
                    $remoteSize = @ftp_size($conn, $remotePath);
                    if ($remoteSize === $localSize) {
                        return 'skipped';
                    }
                }

                $this->transfersSinceConnect++;
                $uploadMode = (preg_match('/\.(php|blade\.php|js|css|json|env|txt|xml|md|scss)$/i', $localPath)) ? FTP_ASCII : FTP_BINARY;

                if (@ftp_put($conn, $remotePath, $localPath, $uploadMode)) {
                    return 'uploaded';
                }

                // If failed, reconnect and retry
                $this->connect();
            } catch (Exception $e) {
                $this->connect();
            }
        }
        return 'failed';
    }

    public function close() {
        if ($this->conn) {
            @ftp_close($this->conn);
            $this->conn = null;
        }
    }
}

$sync = new FtpSync($ftp_server, $ftp_user, $ftp_pass);
$sync->connect();
echo "Connected successfully to {$ftp_server}!\n\n";

// Phase 1: Code directories (Force overwrite all code files to ensure 100% version parity)
$codeDirs = [
    'app',
    'bootstrap',
    'config',
    'database',
    'resources',
    'routes',
    'public/build',
];

$rootFiles = [
    'vite.config.js',
];

$baseDir = dirname(__DIR__);
$codeFiles = [];

foreach ($codeDirs as $dir) {
    $fullDir = $baseDir . '/' . $dir;
    if (!is_dir($fullDir)) continue;
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($fullDir, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $item) {
        if ($item->isFile()) {
            $rel = str_replace('\\', '/', substr($item->getPathname(), strlen($baseDir) + 1));
            if (str_ends_with($rel, '~') || str_ends_with($rel, '.tmp') || str_contains($rel, '.DS_Store')) continue;
            $codeFiles[] = $rel;
        }
    }
}

foreach ($rootFiles as $rf) {
    if (file_exists($baseDir . '/' . $rf)) {
        $codeFiles[] = $rf;
    }
}

echo "=== PHASE 1: Syncing " . count($codeFiles) . " Code & Build Files ===\n";
$uploadedCode = 0;
$failedCode = [];

foreach ($codeFiles as $idx => $relFile) {
    $local = $baseDir . '/' . $relFile;
    $res = $sync->uploadFile($local, $relFile, true);
    if ($res === 'uploaded') {
        $uploadedCode++;
        if ($uploadedCode % 25 === 0 || $idx === count($codeFiles) - 1) {
            echo "Uploaded $uploadedCode / " . count($codeFiles) . " ($relFile)\n";
        }
    } else {
        $failedCode[] = $relFile;
        echo "FAIL: $relFile\n";
    }
}

echo "\nPhase 1 Complete. Uploaded: $uploadedCode, Failed: " . count($failedCode) . "\n\n";

// Phase 2: Check & Sync Images in public/img (skip if already exists with same size)
echo "=== PHASE 2: Checking and Syncing Images in public/img ===\n";
$imgDir = $baseDir . '/public/img';
$imgFiles = [];
if (is_dir($imgDir)) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($imgDir, RecursiveDirectoryIterator::SKIP_DOTS));
    foreach ($it as $item) {
        if ($item->isFile()) {
            $rel = str_replace('\\', '/', substr($item->getPathname(), strlen($baseDir) + 1));
            $imgFiles[] = $rel;
        }
    }
}

$uploadedImgs = 0;
$skippedImgs = 0;
$failedImgs = [];

foreach ($imgFiles as $idx => $relFile) {
    $local = $baseDir . '/' . $relFile;
    $res = $sync->uploadFile($local, $relFile, false); // only upload if missing or different size
    if ($res === 'uploaded') {
        $uploadedImgs++;
        echo "+ Uploaded new/modified image: $relFile\n";
    } elseif ($res === 'skipped') {
        $skippedImgs++;
    } else {
        $failedImgs[] = $relFile;
        echo "FAIL Image: $relFile\n";
    }

    if (($idx + 1) % 100 === 0) {
        echo "Checked " . ($idx + 1) . " / " . count($imgFiles) . " images (Uploaded: $uploadedImgs, Skipped: $skippedImgs)...\n";
    }
}

echo "\nPhase 2 Complete. Uploaded: $uploadedImgs, Skipped: $skippedImgs, Failed: " . count($failedImgs) . "\n\n";

$sync->close();
echo "Sync completed successfully!\n";

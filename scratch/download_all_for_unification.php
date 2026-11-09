<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

$ftp_server = 'ftp.andeanexclusivetours.com';
$ftp_user   = 'andean@andeanexclusivetours.com';
$ftp_pass   = 'AndeanExclusive12!';

$stagingDir = __DIR__ . '/production_sync';
if (!is_dir($stagingDir)) mkdir($stagingDir, 0777, true);

// Read list of files uploaded from other machine
$jsonPath = __DIR__ . '/files_uploaded_from_other_machine.json';
$filesToDownload = [];

// Also read views and other identified files
$manualList = [
    'app/Http/Controllers/ClienteController.php',
    'app/Http/Controllers/TourController.php',
    'app/Http/Controllers/EsTourController.php',
    'app/Http/Controllers/EnblogController.php',
    'app/Http/Controllers/EsblogController.php',
    'app/Http/Controllers/EnCatController.php',
    'app/Http/Controllers/EsCatController.php',
    'app/Http/Controllers/EntagController.php',
    'app/Http/Controllers/EstagController.php',
    'app/Support/AdminNavigation.php',
    'app/Support/Ayuda.php',
    'database/seeders/AvisosSeeder.php',
    'database/seeders/PeruProveedoresHotelesSeeder.php',
    'database/seeders/ProgramaSeeder.php',
    'database/seeders/RolePermissionSeeder.php',
    'database/seeders/TarifaSeeder.php',
    'resources/js/image-preview.js',
    'resources/js/admin.js',
    'resources/js/programa-form.js',
    'resources/sass/admin.scss',
    'resources/sass/components/_tour-detail.scss',
    'resources/views/admin/partials/image-preview-modal.blade.php',
    'resources/views/admin/tours/create.blade.php',
    'resources/views/admin/tours/edit.blade.php',
    'resources/views/admin/tours/index.blade.php',
    'resources/views/admin/tours/categorias/create.blade.php',
    'resources/views/admin/tours/categorias/edit.blade.php',
    'resources/views/admin/tours/categorias/index.blade.php',
    'resources/views/admin/tours/categorias/show.blade.php',
    'resources/views/admin/estours/create.blade.php',
    'resources/views/admin/estours/edit.blade.php',
    'resources/views/admin/estours/index.blade.php',
    'resources/views/admin/estours/categorias/create.blade.php',
    'resources/views/admin/estours/categorias/edit.blade.php',
    'resources/views/admin/estours/categorias/index.blade.php',
    'resources/views/admin/estours/categorias/show.blade.php',
    'resources/views/admin/enblogs/create.blade.php',
    'resources/views/admin/enblogs/edit.blade.php',
    'resources/views/admin/enblogs/index.blade.php',
    'resources/views/admin/enblogs/show.blade.php',
    'resources/views/admin/enblogs/tags/create.blade.php',
    'resources/views/admin/enblogs/tags/edit.blade.php',
    'resources/views/admin/enblogs/tags/index.blade.php',
    'resources/views/admin/enblogs/tags/show.blade.php',
    'resources/views/admin/esblogs/create.blade.php',
    'resources/views/admin/esblogs/edit.blade.php',
    'resources/views/admin/esblogs/index.blade.php',
    'resources/views/admin/esblogs/show.blade.php',
    'resources/views/admin/esblogs/tags/create.blade.php',
    'resources/views/admin/esblogs/tags/edit.blade.php',
    'resources/views/admin/esblogs/tags/index.blade.php',
    'resources/views/admin/esblogs/tags/show.blade.php',
    'resources/views/clientes/index.blade.php',
    'resources/views/errors/403.blade.php',
    'resources/views/errors/404.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/programas/form.blade.php',
    'resources/views/programas/partials/servicio-row.blade.php',
    'routes/web.php',
    'package.json',
];

$filesToDownload = array_unique($manualList);

function getFtpConnection($server, $user, $pass) {
    $conn = @ftp_ssl_connect($server, 21, 20);
    if ($conn && @ftp_login($conn, $user, $pass)) {
        ftp_pasv($conn, true);
        return $conn;
    }
    return null;
}

$conn = getFtpConnection($ftp_server, $ftp_user, $ftp_pass);
if (!$conn) {
    die("FTP connection failed\n");
}

echo "Downloading " . count($filesToDownload) . " files to staging ($stagingDir)...\n\n";

$downloadCount = 0;
$failCount = 0;

foreach ($filesToDownload as $i => $file) {
    $targetPath = $stagingDir . '/' . $file;
    $targetDir = dirname($targetPath);
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Reconnect every 10 downloads to avoid stale connection timeout
    if ($i > 0 && $i % 10 === 0) {
        @ftp_close($conn);
        $conn = getFtpConnection($ftp_server, $ftp_user, $ftp_pass);
    }

    $ok = @ftp_get($conn, $targetPath, $file, FTP_BINARY);
    if (!$ok) {
        // Retry once with fresh connection
        @ftp_close($conn);
        $conn = getFtpConnection($ftp_server, $ftp_user, $ftp_pass);
        $ok = @ftp_get($conn, $targetPath, $file, FTP_BINARY);
    }

    if ($ok) {
        $downloadCount++;
        echo sprintf(" [%2d/%2d] OK: %s (%d bytes)\n", $i + 1, count($filesToDownload), $file, filesize($targetPath));
    } else {
        $failCount++;
        echo sprintf(" [%2d/%2d] FAILED: %s\n", $i + 1, count($filesToDownload), $file);
    }
}

echo "\nSummary: $downloadCount downloaded, $failCount failed.\n";
@ftp_close($conn);

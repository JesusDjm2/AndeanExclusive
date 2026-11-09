<?php
$avatarPath = 'c:/xampp/htdocs/AndeanExclusive/public/img/default-avatar.png';
if (!file_exists($avatarPath)) {
    // Create a 120x120 clean grey avatar circle using GD
    $im = imagecreatetruecolor(120, 120);
    imagesavealpha($im, true);
    $transparent = imagecolorallocatealpha($im, 0, 0, 0, 127);
    imagefill($im, 0, 0, $transparent);

    $bg = imagecolorallocate($im, 226, 232, 240); // slate-200
    imagefilledellipse($im, 60, 60, 120, 120, $bg);

    $head = imagecolorallocate($im, 148, 163, 184); // slate-400
    imagefilledellipse($im, 60, 45, 42, 42, $head);
    imagefilledellipse($im, 60, 105, 80, 65, $head);

    imagepng($im, $avatarPath);
    imagedestroy($im);
    echo "Created default-avatar.png\n";
} else {
    echo "default-avatar.png exists\n";
}

<?php
$url = 'https://andean.pachamamaspirit.com/ae_post_deploy.php?token=ae_secret_deploy_2026';
$ctx = stream_context_create([
    'http' => ['timeout' => 30],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
]);

echo "Calling $url ...\n";
$res = file_get_contents($url, false, $ctx);
echo $res . "\n";

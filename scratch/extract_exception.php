<?php
$ch = curl_init('https://andeanexclusivetours.com/about');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$res = curl_exec($ch);
curl_close($ch);

if (preg_match('/window\.data\s*=\s*(.*?);\s*<\/script>/s', $res, $m)) {
    $data = json_decode($m[1], true);
    if ($data && isset($data['report'])) {
        echo "Exception: " . $data['report']['message'] . "\n";
        echo "File: " . $data['report']['file'] . ":" . $data['report']['line_number'] . "\n";
        echo "Class: " . $data['report']['class'] . "\n";
    } else {
        echo "JSON decoded but no report key\n";
    }
} else {
    // Check for standard laravel exception
    if (preg_match('/Exception: (.*?)\n/s', $res, $m2)) {
        echo "Exception: " . $m2[1] . "\n";
    }
}

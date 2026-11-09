<?php
$locale = 'en';
$isEs_buggy = ($locale ?? false);
var_dump((bool)$isEs_buggy); // bool(true) - BUG!

$isEs_fixed = isset($locale) ? ($locale === 'es') : false;
var_dump($isEs_fixed); // bool(false) - CORRECT!

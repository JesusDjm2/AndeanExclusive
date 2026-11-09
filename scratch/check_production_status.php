<?php
$ctx = stream_context_create(['http' => ['timeout' => 10, 'follow_location' => 0]]);
$headers = @get_headers('https://andean.pachamamaspirit.com/proveedors', false, $ctx);
echo "Proveedors HTTP Headers:\n";
print_r($headers);

$headers2 = @get_headers('https://andean.pachamamaspirit.com/movimientos', false, $ctx);
echo "Movimientos HTTP Headers:\n";
print_r($headers2);

$headers3 = @get_headers('https://andean.pachamamaspirit.com/cuentas-por-pagar', false, $ctx);
echo "Cuentas HTTP Headers:\n";
print_r($headers3);

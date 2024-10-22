<?php

$ip = '1.1.1.1'; 

$response = file_get_contents("http://ipinfo.io/{$ip}/json");
$data = json_decode($response, true);
$provider = $data['org'] ?? 'Provider nicht gefunden';

echo "Provider: " . $provider;

?>
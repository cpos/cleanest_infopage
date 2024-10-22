<?php

// get all relevant data
$client_ip = $_SERVER['REMOTE_ADDR']; // client-IP
$server_ip = $_SERVER['SERVER_ADDR']; // server-IP

$client_port = $_SERVER['REMOTE_PORT']; // client-Port
$server_port = $_SERVER['SERVER_PORT']; // server-Port

$host = gethostbyaddr($client_ip); // reverse-DNS-Lookup client-IP
$server_host = gethostbyaddr($server_ip); // reverse-DNS-Lookup client-IP

$response = file_get_contents("http://ipinfo.io/{$client_ip}/json");
$data = json_decode($response, true);
$provider = $data['org'] ?? 'unknown';
$city = $data['city'] ?? 'unknown';
$country = $data['country'] ?? 'unknown';

$user_agent = $_SERVER['HTTP_USER_AGENT']; // User-Agent
$request_time = date("l, d-M-Y H:i:s T"); // current time 
$accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE']; // preferred language
$accept_encoding = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '(none)'; // accepted encoding
$accept = $_SERVER['HTTP_ACCEPT']; // accepted HTTP types
$http_version = $_SERVER['SERVER_PROTOCOL']; // HTTP-version
$request_method = $_SERVER['REQUEST_METHOD']; // Request-method

$connection = $_SERVER['HTTP_CONNECTION']; 
$is_encrypted = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

// Check if the user agent is curl
if (stripos($user_agent, 'curl') !== false) {
    // Output plain text for curl
    header('Content-Type: text/plain');
    echo "Encrypted: " . ($is_encrypted ? 'Yes' : 'No') . "\n";
    echo "Client IP: " . $client_ip . ' : ' . $client_port . "\n";
    echo "Client Host: " . $host . "\n";
    echo "Provider: " . $provider ."\n";
    echo "Country: " . $country . ' in City: ' . $city ."\n";
    echo "Server IP: " . $server_ip . "\n";
    echo "Server Port: " . $server_port . "\n";
    echo "Server Host: " . $server_host . "\n";
    echo "Request Time: " . $request_time . "\n";
    echo "User Agent: " . $user_agent . "\n";
    echo "HTTP Version: " . $http_version . "\n";
    echo "Request Method: " . $request_method . "\n";
    echo "Accepted Encoding: " . $accept_encoding . "\n";
    echo "Accepted HTTP: " . $accept . "\n";    
} else {
    // Output HTML as usual for normal browsers
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>connection infopage</title>
    <link type="text/css" rel="stylesheet" href="style.css">
</head>
<script type="text/javascript" src="speedtest.js"></script>
<body>

    <span class="<?= $is_encrypted ? 'mark_green' : 'mark_red' ?>">
        <?= $is_encrypted ? 'encrypted' : 'unencrypted' ?>
    </span>

    <span class="mark_red">IPv4</span>

    request
    <br />from

    <span class="mark_blue">
        <?= $client_ip ?>:<?= $client_port ?>
    </span> 

    <span class="mark_green">
        <b>(<?= $host ?>)</b>
    </span>

    <br>with provider

    <span class="mark_green">
    <b>(<?= $provider ?>)</b>
    </span>

    <br>in country
    <span class="mark_blue">
    (<?= $country ?>) 
    </span>

    and city 
    <span class="mark_blue">
    (<?= $city ?>) 
    </span>


    </span>

    <br>to

    <span class="mark_blue">
    <?= $server_ip ?>:<?= $server_port ?>
    </span>

    <span class="mark_green">
        <b>(<?= $server_host ?>)</b>
    </span>

    <br />at
    <span class="mark_blue"><?= $request_time ?></span>

    <br />using
    <span class="mark_blue"><?= $user_agent ?></span>

    <br />with
    <span class="mark_blue"><?= $http_version ?></span>

    using request-method
    <span class="mark_blue"><?= $request_method ?></span>

    on
    <span class="mark_blue"><?= $connection ?></span> connection

    <br />preferring
    <span class="mark_blue"><?= $accept_language ?></span>

    encoded as
    <span class="mark_blue"><?= $accept_encoding ?></span>

    <br />while generally accepting
    <span class="mark_blue"><?= $accept ?></span>

    <br>

     <table> 
        <tr>
            <td>ping:&nbsp;</td>
            <td id="ping" class="mark_blue"></td>
        </tr>
    </table>
    <table>
        <tr>
            <td>download speed: &nbsp;</td>
            <td id="download" class="mark_blue"></td>
        </tr>
        <tr>
            <td>upload speed: &nbsp;</td>
            <td id="upload" class="mark_blue"></td>
        </tr>
    </table>

    <script type="text/javascript">
        var s = new Speedtest();
        s.onupdate = function (data) { // when status is received, put the values in the appropriate fields
            document.getElementById('download').textContent = data.dlStatus + ' Mbit/s'
            document.getElementById('upload').textContent = data.ulStatus + ' Mbit/s'
            document.getElementById('ping').textContent = data.pingStatus + ' ms, ' + data.jitterStatus + ' ms jitter'
        }
        s.start(); // start the speed test with default settings
    </script>


</body>
</html>

<?php
}
?>
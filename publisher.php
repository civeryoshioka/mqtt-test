use Bluerhinos\phpMQTT;

<?php
require 'vendor/autoload.php'; // pastikan phpMQTT sudah diinstall via Composer
use Bluerhinos\phpMQTT;

$server   = '103.129.148.198';          // broker MQTT publik
$port     = 1883;                 // port MQTT
$username = '';                   // jika broker membutuhkan username
$password = '';                   // jika broker membutuhkan password
$client_id = 'phpMQTTpublisher';

$mqtt = new phpMQTT($server, $port, $client_id);

if ($mqtt->connect(true, NULL, $username, $password)) {
    $topic = 'test/topic1';
    //$message = 'Luwe rung madang';
    $qos = 0; // Quality of Service
    $retain = true ;
    $dataSensor = [
        'suhu' => 30.5,
        'kelembapan' => 65.2,
        'tekanan' => 1012.6
    ];
    $payload = json_encode($dataSensor);
    $mqtt->publish($topic, $payload, $qos, $retain);
    $mqtt->close();
    echo "Message published!\n";
} else {
    echo "Failed to connect to MQTT broker.\n";
}
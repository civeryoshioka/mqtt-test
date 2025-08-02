use Bluerhinos\phpMQTT;

<?php
require 'vendor/autoload.php'; // pastikan phpMQTT sudah diinstall via Composer
use Bluerhinos\phpMQTT;

$server   = 'localhost';          // broker MQTT publik
$port     = 1883;                 // port MQTT
$username = '';                   // jika broker membutuhkan username
$password = '';                   // jika broker membutuhkan password
$client_id = 'phpMQTTpublisher';

$mqtt = new phpMQTT($server, $port, $client_id);

if ($mqtt->connect(true, NULL, $username, $password)) {
    $topic = 'test/topic1';
    $message = 'Hello MQTT from PHP!';
    $qos = 0; // Quality of Service
    $retain = true ;

    $mqtt->publish($topic, $message, $qos, $retain);
    $mqtt->close();
    echo "Message published!\n";
} else {
    echo "Failed to connect to MQTT broker.\n";
}


<?php
require('vendor/autoload.php'); // Pastikan sudah install php-mqtt/client via Composer

use Bluerhinos\phpMQTT;
$server   = 'localhost';
$port     = 1883;
$clientId = 'phpMQTTpublisher';
//$topic    = 'test/topic';



$mqtt = new phpMQTT($server, $port, $clientId);

if (!$mqtt->connect(true, NULL)) {
    exit("Gagal konek ke broker MQTT.\n");
}

$topics = ["test/topic1" => ["qos" => 0, "function" => "terimaPesan"]];
$mqtt->subscribe($topics, 0);

while ($mqtt->proc()) {}

$mqtt->close();

function terimaPesan($topic, $message) {
    echo "Pesan diterima dari [$topic]: $message\n";
    exit;
}


<?php
require('vendor/autoload.php'); // Pastikan sudah install php-mqtt/client via Composer

use Bluerhinos\phpMQTT;
$server   = 'dcows.berdikari.pens.ac.id';
$port     = 1883;
$username = "dcows";    // Ganti dengan username broker
$password = "dcows123";  
$clientId = 'phpMQTTpublisher';
$topic    = 'application/4674110f-9988-41c2-8ead-12ef5e9ca344/device/b43b115777468f27/event/up';



$mqtt = new phpMQTT($server, $port, $clientId);

if (!$mqtt->connect(true, NULL,$username, $password)) {
    exit("Gagal konek ke broker MQTT.\n");
}

$topics['application/4674110f-9988-41c2-8ead-12ef5e9ca344/device/b43b115777468f27/event/up'] = [
    'qos' => 0,
    'function' => function($topic, $msg) {
        echo "Pesan diterima dari Topik [$topic]:\n";
        $data = json_decode($msg, true);
        
        echo "Voltage : {$data['object']['voltage']} \n";
        echo "lampState : {$data['object']['lampState']} \n";
        echo "counter : {$data['object']['counter']} \n";
        echo "frequency : {$data['object']['frequency']} \n";
        echo "powerFactor : {$data['object']['powerFactor']} \n";
        $timestamp = $data['object']['datetime'];
        $datetime = date("Y-m-d H:i:s", $timestamp);
        echo "datetime : $datetime \n";
        echo "brightness : {$data['object']['brightness']} \n";
        echo "errorState : {$data['object']['errorState']} \n";  
        echo "current : {$data['object']['current']} \n";
        echo "energy : {$data['object']['energy']} \n";
        echo "errorState : {$data['object']['errorState']} \n";
        echo "nodeId : {$data['object']['nodeId']} \n";
        echo "power : {$data['object']['power']} \n";
        echo "temperature : {$data['object']['temperature']} \n";
        echo "------------------------\n";
    }
];

$mqtt->subscribe($topics, 0);

// Jalankan sampai ada 1 pesan diterima
$start = time();
while ($mqtt->proc()) {
    if (time() - $start > 1000) break; // maksimal 10 detik
}
$mqtt->close();
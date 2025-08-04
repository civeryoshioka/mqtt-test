

<?php
require('vendor/autoload.php'); // Pastikan sudah install php-mqtt/client via Composer
include 'koneksi.php'; // Pastikan koneksi.php sudah benar

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
echo "Server [$server]\n";
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

        global $conn; // pastikan $conn dari koneksi.php

        $voltage      = $data['object']['voltage'];
        $lampState    = $data['object']['lampState'];
        $counter      = $data['object']['counter'];
        $frequency    = $data['object']['frequency'];
        $powerFactor  = $data['object']['powerFactor'];
        $datetime     = date("Y-m-d H:i:s", $data['object']['datetime']);
        $brightness   = $data['object']['brightness'];
        $errorState   = $data['object']['errorState'];
        $current      = $data['object']['current'];
        $energy       = $data['object']['energy'];
        $nodeId       = $data['object']['nodeId'];
        $power        = $data['object']['power'];
        $temperature  = $data['object']['temperature'];

        $sql = "INSERT INTO sensor (voltage, lampState, counter, frequency, powerFactor, datetime, brightness, errorState, current, energy, nodeId, power, temperature)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ididssssddids",
            $voltage,
            $lampState,
            $counter,
            $frequency,
            $powerFactor,
            $datetime,
            $brightness,
            $errorState,
            $current,
            $energy,
            $nodeId,
            $power,
            $temperature
        );

        if ($stmt->execute()) {
            echo "Data berhasil disimpan ke database.\n";
        } else {
            echo "Gagal menyimpan data: " . $stmt->error . "\n";
        }
        $stmt->close();
    }
];

$mqtt->subscribe($topics, 0);

// Jalankan sampai ada 1 pesan diterima
$start = time();
while ($mqtt->proc(true,60)) {
    if (time() - $start > 10000) break; // maksimal 10 detik
}
$mqtt->close();
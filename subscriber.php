

<?php
require('vendor/autoload.php'); // Pastikan sudah install php-mqtt/client via Composer

use Bluerhinos\phpMQTT;
$server   = '103.129.148.198';
$port     = 1883;
$clientId = 'phpMQTTpublisher';
//$topic    = 'test/topic';



$mqtt = new phpMQTT($server, $port, $clientId);

if (!$mqtt->connect(true, NULL)) {
    exit("Gagal konek ke broker MQTT.\n");
}

$topics['test/topic1'] = [
    'qos' => 0,
    'function' => function($topic, $msg) {
        echo "Pesan diterima dari topik [$topic]:\n";
        $data = json_decode($msg, true);
        echo "Suhu: {$data['suhu']} °C\n";
        echo "Kelembapan: {$data['kelembapan']} %\n";
        echo "Tekanan: {$data['tekanan']} hPa\n";
    }
];

$mqtt->subscribe($topics, 0);

// Jalankan sampai ada 1 pesan diterima
$start = time();
while ($mqtt->proc()) {
    if (time() - $start > 10) break; // maksimal 10 detik
}
$mqtt->close();
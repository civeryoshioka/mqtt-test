<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'smart_pju';

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Jika berhasil
// echo "Koneksi berhasil";
?>
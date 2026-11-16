<?php
// Konfigurasi koneksi database PostgreSQL menggunakan PDO
$host = 'localhost';
$port = '5432';
$dbname = 'db_penjualan_test'; 
$user = 'postgres';     
$password = 'Rakmalaz611'; 

try {
    // Membuat instance objek PDO untuk PostgreSQL
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    
    // Mengatur mode error PDO menjadi Exception agar error mudah dideteksi
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // echo "Koneksi berhasil!"; // Uncomment baris ini jika ingin mengetes koneksi
} catch (PDOException $e) {
    // Jika koneksi gagal, tampilkan pesan error
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
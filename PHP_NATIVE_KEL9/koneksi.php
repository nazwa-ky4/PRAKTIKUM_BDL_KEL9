<?php
// Konfigurasi koneksi database PostgreSQL menggunakan PDO
$host = 'localhost';
$port = '5432';
$dbname = 'db_penjualan'; // Nama database utama kita
$user = 'postgres';       // Username default PostgreSQL
$password = 'nazwa2007'; // ⚠️ Ganti dengan password database PostgreSQL di pgAdmin

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
<?php
// C.1 DELETE produk lalu kembali ke daftar. Gagal jika produk sudah dipakai transaksi (foreign key).
require_once 'koneksi.php';

// Periksa apakah parameter id tersedia di URL
if (isset($_GET['id'])) {
    $kode_produk = $_GET['id'];

    try {
        // Eksekusi query delete berdasarkan kode_produk
        $stmt = $pdo->prepare("DELETE FROM produk WHERE kode_produk = ?");
        $stmt->execute([$kode_produk]);
    } catch (PDOException $e) {
        // Jika data gagal dihapus (misal karena masih terikat foreign key di tabel penjualan/pembelian)
        echo "<script>alert('Gagal menghapus produk: Data sedang digunakan dalam transaksi!'); window.location.href='index.php';</script>";
        exit;
    }
}

// Redirect kembali ke halaman index
header("Location: index.php");
exit;
?>
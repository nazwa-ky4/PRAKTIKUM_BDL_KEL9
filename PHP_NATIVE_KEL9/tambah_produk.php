<?php
// C.1 CREATE: form tambah + proses INSERT dalam satu file.
require_once 'koneksi.php';

$error = '';

// Jalan saat tombol Simpan diklik (POST).
if (isset($_POST['submit'])) {
    $kode_produk = trim($_POST['kode_produk']);
    $nama_produk = trim($_POST['nama_produk']);
    $satuan      = trim($_POST['satuan']);
    $harga_jual  = trim($_POST['harga_jual']);
    $harga_beli  = trim($_POST['harga_beli']);
    $stok        = trim($_POST['stok']);
    $deskripsi   = trim($_POST['deskripsi']);

    // Validasi sederhana
    if ($kode_produk === '' || $nama_produk === '' || $satuan === '' || $harga_jual === '' || $harga_beli === '' || $stok === '') {
        $error = "Semua kolom wajib diisi kecuali deskripsi!";
    } else {
        try {
            // INSERT langsung dengan placeholder ?; urutan nilai di execute() harus sama.
            $sql = "INSERT INTO produk (kode_produk, nama_produk, satuan, harga_jual, harga_beli, stok, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$kode_produk, $nama_produk, $satuan, $harga_jual, $harga_beli, $stok, $deskripsi]);

            // Redirect kembali ke index setelah sukses
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            // Mis. kode produk sudah ada (primary key).
            $error = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk Baru</title>
</head>
<body>
<?php require 'menu.php'; ?>

<h2>Tambah Produk Baru</h2>

<?php if ($error): ?>
    <p><strong>[GAGAL]</strong> <?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="" method="POST">
    <p><label>Kode Produk (Contoh: P006):</label><br>
        <input type="text" name="kode_produk" required value="<?= isset($_POST['kode_produk']) ? htmlspecialchars($_POST['kode_produk']) : '' ?>"></p>
    <p><label>Nama Produk:</label><br>
        <input type="text" name="nama_produk" required value="<?= isset($_POST['nama_produk']) ? htmlspecialchars($_POST['nama_produk']) : '' ?>"></p>
    <p><label>Satuan (Contoh: buah, rim, botol):</label><br>
        <input type="text" name="satuan" required value="<?= isset($_POST['satuan']) ? htmlspecialchars($_POST['satuan']) : '' ?>"></p>
    <p><label>Harga Jual (Rp):</label><br>
        <input type="number" name="harga_jual" step="0.01" required value="<?= isset($_POST['harga_jual']) ? htmlspecialchars($_POST['harga_jual']) : '' ?>"></p>
    <p><label>Harga Beli (Rp):</label><br>
        <input type="number" name="harga_beli" step="0.01" required value="<?= isset($_POST['harga_beli']) ? htmlspecialchars($_POST['harga_beli']) : '' ?>"></p>
    <p><label>Stok:</label><br>
        <input type="number" name="stok" required value="<?= isset($_POST['stok']) ? htmlspecialchars($_POST['stok']) : '' ?>"></p>
    <p><label>Deskripsi (Opsional):</label><br>
        <textarea name="deskripsi" rows="3" cols="40"><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea></p>

    <button type="submit" name="submit">Simpan Produk</button>
    <a href="index.php">Kembali</a>
</form>

</body>
</html>
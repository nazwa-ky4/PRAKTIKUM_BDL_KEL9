<?php
// C.1 UPDATE: ambil data lama, tampilkan di form, simpan dengan UPDATE.
require_once 'koneksi.php';

$error = '';

// Mengambil kode_produk dari URL parameter ?id=...
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$kode_produk = $_GET['id'];

// Ambil data produk berdasarkan kode_produk
$stmt = $pdo->prepare("SELECT * FROM produk WHERE kode_produk = ?");
$stmt->execute([$kode_produk]);
$produk = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produk) {
    header("Location: index.php");
    exit;
}

// Proses form jika tombol update diklik
if (isset($_POST['update'])) {
    $nama_produk = trim($_POST['nama_produk']);
    $satuan      = trim($_POST['satuan']);
    $harga_jual  = trim($_POST['harga_jual']);
    $harga_beli  = trim($_POST['harga_beli']);
    $stok        = trim($_POST['stok']);
    $deskripsi   = trim($_POST['deskripsi']);

    if ($nama_produk === '' || $satuan === '' || $harga_jual === '' || $harga_beli === '' || $stok === '') {
        $error = "Semua kolom wajib diisi kecuali deskripsi!";
    } else {
        try {
            // WHERE wajib agar hanya 1 produk yang berubah.
            $sql = "UPDATE produk SET nama_produk = ?, satuan = ?, harga_jual = ?, harga_beli = ?, stok = ?, deskripsi = ? WHERE kode_produk = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$nama_produk, $satuan, $harga_jual, $harga_beli, $stok, $deskripsi, $kode_produk]);

            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "Gagal memperbarui data: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Produk</title>
</head>
<body>
<?php require 'menu.php'; ?>

<h2>Edit Produk: <?= htmlspecialchars($produk['kode_produk']) ?></h2>

<?php if ($error): ?>
    <p><strong>[GAGAL]</strong> <?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form action="" method="POST">
    <p><label>Kode Produk (Tidak dapat diubah):</label><br>
        <?php // Kode (primary key) tidak bisa diubah. ?>
        <input type="text" value="<?= htmlspecialchars($produk['kode_produk']) ?>" disabled></p>
    <p><label>Nama Produk:</label><br>
        <input type="text" name="nama_produk" required value="<?= htmlspecialchars($produk['nama_produk']) ?>"></p>
    <p><label>Satuan:</label><br>
        <input type="text" name="satuan" required value="<?= htmlspecialchars($produk['satuan']) ?>"></p>
    <p><label>Harga Jual (Rp):</label><br>
        <input type="number" name="harga_jual" step="0.01" required value="<?= htmlspecialchars($produk['harga_jual']) ?>"></p>
    <p><label>Harga Beli (Rp):</label><br>
        <input type="number" name="harga_beli" step="0.01" required value="<?= htmlspecialchars($produk['harga_beli']) ?>"></p>
    <p><label>Stok:</label><br>
        <input type="number" name="stok" required value="<?= htmlspecialchars($produk['stok']) ?>"></p>
    <p><label>Deskripsi:</label><br>
        <textarea name="deskripsi" rows="3" cols="40"><?= htmlspecialchars($produk['deskripsi']) ?></textarea></p>

    <button type="submit" name="update">Simpan Perubahan</button>
    <a href="index.php">Kembali</a>
</form>

</body>
</html>
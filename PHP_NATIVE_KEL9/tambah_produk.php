<?php
require_once 'koneksi.php';

$error = '';
$sukses = '';

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
            $sql = "INSERT INTO produk (kode_produk, nama_produk, satuan, harga_jual, harga_beli, stok, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$kode_produk, $nama_produk, $satuan, $harga_jual, $harga_beli, $stok, $deskripsi]);
            
            // Redirect kembali ke index setelah sukses
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $error = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Produk - PHP Native</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; }
        .form-container { background: #fff; padding: 20px; border-radius: 6px; max-width: 500px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { resize: vertical; height: 80px; }
        .btn-submit { background: #28a745; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .btn-kembali { background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Tambah Produk Baru</h2>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Kode Produk (Contoh: P006):</label>
                <input type="text" name="kode_produk" required value="<?= isset($_POST['kode_produk']) ? htmlspecialchars($_POST['kode_produk']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Nama Produk:</label>
                <input type="text" name="nama_produk" required value="<?= isset($_POST['nama_produk']) ? htmlspecialchars($_POST['nama_produk']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Satuan (Contoh: buah, rim, botol):</label>
                <input type="text" name="satuan" required value="<?= isset($_POST['satuan']) ? htmlspecialchars($_POST['satuan']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Harga Jual (Rp):</label>
                <input type="number" name="harga_jual" step="0.01" required value="<?= isset($_POST['harga_jual']) ? htmlspecialchars($_POST['harga_jual']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Harga Beli (Rp):</label>
                <input type="number" name="harga_beli" step="0.01" required value="<?= isset($_POST['harga_beli']) ? htmlspecialchars($_POST['harga_beli']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Stok:</label>
                <input type="number" name="stok" required value="<?= isset($_POST['stok']) ? htmlspecialchars($_POST['stok']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Deskripsi (Opsional):</label>
                <textarea name="deskripsi"><?= isset($_POST['deskripsi']) ? htmlspecialchars($_POST['deskripsi']) : '' ?></textarea>
            </div>

            <button type="submit" name="submit" class="btn-submit">Simpan Produk</button>
            <a href="index.php" class="btn-kembali">Kembali</a>
        </form>
    </div>

</body>
</html>
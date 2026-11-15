<?php
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
    <title>Edit Produk - PHP Native</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; }
        .form-container { background: #fff; padding: 20px; border-radius: 6px; max-width: 500px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { resize: vertical; height: 80px; }
        .btn-submit { background: #ffc107; color: #333; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .btn-kembali { background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Edit Produk: <?= htmlspecialchars($produk['kode_produk']) ?></h2>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Kode Produk (Tidak dapat diubah):</label>
                <input type="text" value="<?= htmlspecialchars($produk['kode_produk']) ?>" disabled style="background-color: #e9ecef;">
            </div>
            <div class="form-group">
                <label>Nama Produk:</label>
                <input type="text" name="nama_produk" required value="<?= htmlspecialchars($produk['nama_produk']) ?>">
            </div>
            <div class="form-group">
                <label>Satuan:</label>
                <input type="text" name="satuan" required value="<?= htmlspecialchars($produk['satuan']) ?>">
            </div>
            <div class="form-group">
                <label>Harga Jual (Rp):</label>
                <input type="number" name="harga_jual" step="0.01" required value="<?= htmlspecialchars($produk['harga_jual']) ?>">
            </div>
            <div class="form-group">
                <label>Harga Beli (Rp):</label>
                <input type="number" name="harga_beli" step="0.01" required value="<?= htmlspecialchars($produk['harga_beli']) ?>">
            </div>
            <div class="form-group">
                <label>Stok:</label>
                <input type="number" name="stok" required value="<?= htmlspecialchars($produk['stok']) ?>">
            </div>
            <div class="form-group">
                <label>Deskripsi:</label>
                <textarea name="deskripsi"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
            </div>

            <button type="submit" name="update" class="btn-submit">Simpan Perubahan</button>
            <a href="index.php" class="btn-kembali">Kembali</a>
        </form>
    </div>

</body>
</html>
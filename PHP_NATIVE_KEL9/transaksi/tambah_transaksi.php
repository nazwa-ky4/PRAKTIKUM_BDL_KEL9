<?php
require_once '../koneksi.php';

$error = '';
$sukses = '';

// Ambil data produk dan pelanggan untuk pilihan di form
try {
    $stmtProduk = $pdo->query("SELECT * FROM produk WHERE stok > 0 ORDER BY nama_produk ASC");
    $list_produk = $stmtProduk->fetchAll(PDO::FETCH_ASSOC);

    $stmtPelanggan = $pdo->query("SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC");
    $list_pelanggan = $stmtPelanggan->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gagal memuat data referensi: " . $e->getMessage());
}

// Proses ketika form transaksi disubmit
if (isset($_POST['simpan_transaksi'])) {
    $no_penjualan    = trim($_POST['no_penjualan']);
    $tgl_penjualan   = trim($_POST['tgl_penjualan']);
    $kode_pelanggan  = trim($_POST['kode_pelanggan']);
    $kode_produk     = trim($_POST['kode_produk']);
    $jumlah          = (int)$_POST['jumlah'];
    $keterangan      = trim($_POST['keterangan']);

    if ($no_penjualan === '' || $tgl_penjualan === '' || $kode_pelanggan === '' || $kode_produk === '' || $jumlah <= 0) {
        $error = "Semua kolom wajib diisi dengan benar!";
    } else {
        try {
            // 1. MULAI TRANSAKSI DATABASE
            $pdo->beginTransaction();

            // A. Ambil harga produk saat ini untuk disimpan ke detil penjualan
            $stmtP = $pdo->prepare("SELECT harga_jual, stok FROM produk WHERE kode_produk = ?");
            $stmtP->execute([$kode_produk]);
            $prod = $stmtP->fetch(PDO::FETCH_ASSOC);

            if (!$prod) {
                throw new Exception("Produk tidak ditemukan!");
            }

            if ($prod['stok'] < $jumlah) {
                throw new Exception("Stok produk tidak mencukupi! Sisa stok: " . $prod['stok']);
            }

            $harga_satuan = $prod['harga_jual'];
            $total_harga  = $jumlah * $harga_satuan;

            // B. Insert ke tabel penjualan (header)
            $sqlPenjualan = "INSERT INTO penjualan (no_penjualan, tgl_penjualan, kode_pelanggan, total, keterangan) VALUES (?, ?, ?, ?, ?)";
            $stmt1 = $pdo->prepare($sqlPenjualan);
            $stmt1->execute([$no_penjualan, $tgl_penjualan, $kode_pelanggan, $total_harga, $keterangan]);


            // C. Insert ke tabel detil_penjualan (item barang)
            // Menyesuaikan kolom harga berdasarkan deteksi sebelumnya
            $sqlDetil = "INSERT INTO detil_penjualan (no_urut, no_penjualan, kode_produk, jumlah, harga, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt2 = $pdo->prepare($sqlDetil);
            $stmt2->execute([1, $no_penjualan, $kode_produk, $jumlah, $harga_satuan, $total_harga]);

           

            // D. Update / kurangi stok di tabel produk
            $sqlStok = "UPDATE produk SET stok = stok - ? WHERE kode_produk = ?";
            $stmt3 = $pdo->prepare($sqlStok);
            $stmt3->execute([$jumlah, $kode_produk]);

            // 2. JIKA SEMUA BERHASIL, COMMIT PERUBAHAN KE DATABASE
            $pdo->commit();

            header("Location: laporan_penjualan.php");
            exit;

        } catch (Exception $e) {
            // 3. JIKA ADA ERROR, ROLLBACK SEMUA OPERASI AGAR AMAN
            $pdo->rollBack();
            $error = "Transaksi gagal dibatalkan (Rollback): " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Transaksi Baru - Backend Logic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; }
        .form-container { background: #fff; padding: 20px; border-radius: 6px; max-width: 600px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        .btn-submit { background: #007bff; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .btn-kembali { background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Input Transaksi Penjualan Baru</h2>
        <p><i>Fitur ini menerapkan manajemen transaksi Database (<code>beginTransaction</code>, <code>commit</code>, & <code>rollBack</code>).</i></p>

        <?php if ($error): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>No. Penjualan (Contoh: J20261003):</label>
                <input type="text" name="no_penjualan" required value="<?= isset($_POST['no_penjualan']) ? htmlspecialchars($_POST['no_penjualan']) : '' ?>">
            </div>
            <div class="form-group">
                <label>Tanggal Penjualan:</label>
                <input type="date" name="tgl_penjualan" required value="<?= date('Y-m-d') ?>">
            </div>
            <div class="form-group">
                <label>Pilih Pelanggan:</label>
                <select name="kode_pelanggan" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    <?php foreach ($list_pelanggan as $pl): ?>
                        <option value="<?= htmlspecialchars($pl['kode_pelanggan']) ?>">
                            <?= htmlspecialchars($pl['nama_pelanggan']) ?> (<?= htmlspecialchars($pl['alamat']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Pilih Produk:</label>
                <select name="kode_produk" required>
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach ($list_produk as $pr): ?>
                        <option value="<?= htmlspecialchars($pr['kode_produk']) ?>">
                            <?= htmlspecialchars($pr['nama_produk']) ?> - Rp <?= number_format($pr['harga_jual'], 0, ',', '.') ?> (Stok: <?= $pr['stok'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah Beli:</label>
                <input type="number" name="jumlah" min="1" required value="<?= isset($_POST['jumlah']) ? htmlspecialchars($_POST['jumlah']) : 1 ?>">
            </div>
            <div class="form-group">
                <label>Keterangan:</label>
                <textarea name="keterangan"><?= isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : '' ?></textarea>
            </div>

            <button type="submit" name="simpan_transaksi" class="btn-submit">Proses Transaksi & Commit</button>
            <a href="laporan_penjualan.php" class="btn-kembali">Lihat Laporan</a>
        </form>
    </div>

</body>
</html>
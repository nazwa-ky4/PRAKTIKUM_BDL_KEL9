<?php
// Memanggil file koneksi database
require_once 'koneksi.php';

// Menangkap input pencarian jika tombol cari diklik
$keyword = isset($_GET['cari']) ? trim($_GET['cari']) : '';

if ($keyword !== '') {
    // Query pencarian menggunakan ILIKE (khusus PostgreSQL agar pencarian tidak case-sensitive)
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE nama_produk ILIKE ? ORDER BY kode_produk ASC");
    $stmt->execute(["%$keyword%"]);
} else {
    // Query menampilkan seluruh data produk
    $stmt = $pdo->query("SELECT * FROM produk ORDER BY kode_produk ASC");
}

$list_produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk - PHP Native</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background-color: #f4f6f9; }
        h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #343a40; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn { padding: 8px 14px; text-decoration: none; background: #28a745; color: white; border-radius: 4px; display: inline-block; font-weight: bold; }
        .btn-cari { background: #007bff; border: none; color: white; padding: 8px 14px; cursor: pointer; border-radius: 4px; }
        .aksi-edit { color: #ffc107; font-weight: bold; text-decoration: none; }
        .aksi-hapus { color: #dc3545; font-weight: bold; text-decoration: none; }
        form { margin-top: 10px; margin-bottom: 15px; display: flex; gap: 10px; }
    </style>
</head>
<body>

    <h2>Data Produk (PHP Native & PDO)</h2>
    
    <!-- Tombol Tambah Produk & Form Pencarian -->
    <a href="tambah_produk.php" class="btn">+ Tambah Produk Baru</a>

    <form method="GET" action="">
        <input type="text" name="cari" placeholder="Cari nama produk..." value="<?= htmlspecialchars($keyword) ?>" style="padding: 7px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" class="btn-cari">Cari</button>
        <a href="index.php" style="padding: 7px 12px; text-decoration: none; background: #6c757d; color: white; border-radius: 4px;">Reset</a>
    </form>

    <!-- Tabel Data Produk -->
    <table>
        <thead>
            <tr>
                <th>Kode Produk</th>
                <th>Nama Produk</th>
                <th>Satuan</th>
                <th>Harga Jual</th>
                <th>Harga Beli</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($list_produk) > 0): ?>
                <?php foreach ($list_produk as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['kode_produk']) ?></td>
                    <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                    <td><?= htmlspecialchars($p['satuan']) ?></td>
                    <td>Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($p['harga_beli'], 0, ',', '.') ?></td>
                    <td><?= htmlspecialchars($p['stok']) ?></td>
                    <td>
                        <a href="edit_produk.php?id=<?= $p['kode_produk'] ?>" class="aksi-edit">Edit</a> | 
                        <a href="hapus_produk.php?id=<?= $p['kode_produk'] ?>" class="aksi-hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #777;">Tidak ada data produk ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
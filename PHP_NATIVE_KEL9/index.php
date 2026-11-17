<?php
// C.1 READ + cari produk. Pada PHP Native, query, logika, dan tampilan ada dalam satu file.
// Memanggil file koneksi database
require_once 'koneksi.php';

// Menangkap input pencarian jika tombol cari diklik
$keyword = isset($_GET['cari']) ? trim($_GET['cari']) : '';

if ($keyword !== '') {
    // ILIKE: pencarian tidak case-sensitive (khusus PostgreSQL)
    // prepare + ? = prepared statement (aman dari SQL injection).
    $stmt = $pdo->prepare("SELECT * FROM produk WHERE nama_produk ILIKE ? ORDER BY kode_produk ASC");
    $stmt->execute(["%$keyword%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM produk ORDER BY kode_produk ASC");
}

// Ambil semua baris sebagai array asosiatif.
$list_produk = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Produk - PHP Native</title>
</head>
<body>
<?php require 'menu.php'; ?>

<h2>Data Produk — PHP Native (PDO)</h2>

<form method="GET" action="" class="baris-aksi">
    <?php // htmlspecialchars = cegah XSS. ?>
    <input type="text" name="cari" placeholder="Cari nama produk..." value="<?= htmlspecialchars($keyword) ?>">
    <button type="submit">Cari</button>
    <a href="tambah_produk.php" class="btn btn-tambah">+ Tambah Produk</a>
    <?php if ($keyword !== ''): ?>
        <a href="index.php" class="btn btn-reset">Reset</a>
    <?php endif; ?>
</form>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
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
            <?php // number_format = format Rupiah. ?>
            <td>Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($p['harga_beli'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($p['stok']) ?></td>
            <td>
                <?php // Kode produk dikirim lewat URL (?id=). ?>
                <a href="edit_produk.php?id=<?= htmlspecialchars($p['kode_produk']) ?>" class="btn btn-ubah">Ubah</a>
                <a href="hapus_produk.php?id=<?= htmlspecialchars($p['kode_produk']) ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7">Tidak ada data produk ditemukan.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
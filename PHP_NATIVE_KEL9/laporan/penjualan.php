<?php
require_once __DIR__ . '/../koneksi.php';

try {
    $sql = "SELECT p.no_penjualan, p.tgl_penjualan, c.nama_pelanggan, p.total
            FROM penjualan p
            JOIN pelanggan c ON c.kode_pelanggan = p.kode_pelanggan
            ORDER BY p.tgl_penjualan ASC, p.no_penjualan ASC";
    $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gagal mengambil data laporan: " . $e->getMessage());
}

$sukses = $_GET['sukses'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Transaksi Penjualan (JOIN)</title>
</head>
<body>
<?php require __DIR__ . '/../menu.php'; ?>

<?php if ($sukses !== ''): ?>
    <p><strong>[BERHASIL]</strong> Transaksi <?= htmlspecialchars($sukses) ?> berhasil disimpan (COMMIT).</p>
<?php endif; ?>

<h2>Daftar Transaksi Penjualan (JOIN)</h2>
<p>Gabungan tabel penjualan dan pelanggan dengan JOIN.</p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr><th>No. Penjualan</th><th>Tanggal</th><th>Nama Pelanggan</th><th>Total (Rp)</th></tr>
    </thead>
    <tbody>
    <?php if (count($rows) > 0): ?>
        <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['no_penjualan']) ?></td>
            <td><?= htmlspecialchars($r['tgl_penjualan']) ?></td>
            <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
            <td>Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4">Belum ada transaksi.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
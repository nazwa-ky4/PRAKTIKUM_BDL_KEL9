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
    <title>Transaksi Penjualan - PHP Native</title>
</head>
<body>
<?php require __DIR__ . '/../menu.php'; ?>
<div class="container">
    <h2>Daftar Transaksi Penjualan (JOIN) - PHP Native</h2>
    <?php if ($sukses !== ''): ?>
        <div class="sukses">Transaksi <?= htmlspecialchars($sukses) ?> berhasil disimpan (COMMIT).</div>
    <?php endif; ?>
    <p>Gabungan tabel <code>penjualan</code> dan <code>pelanggan</code> dengan <code>JOIN</code>.</p>
    <table>
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
                <td class="angka">Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center;">Belum ada transaksi.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
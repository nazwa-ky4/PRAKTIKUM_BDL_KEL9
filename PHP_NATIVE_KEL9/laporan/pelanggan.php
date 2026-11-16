<?php
require_once __DIR__ . '/../koneksi.php';
try {
    $sql = "SELECT c.kode_pelanggan, c.nama_pelanggan,
                   COUNT(p.no_penjualan) AS jumlah_transaksi,
                   COALESCE(SUM(p.total), 0) AS total_penjualan
            FROM pelanggan c
            LEFT JOIN penjualan p ON p.kode_pelanggan = c.kode_pelanggan
            GROUP BY c.kode_pelanggan, c.nama_pelanggan
            ORDER BY total_penjualan DESC, c.nama_pelanggan ASC";
    $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gagal mengambil data laporan: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Pelanggan - PHP Native</title>
</head>
<body>
<?php require __DIR__ . '/../menu.php'; ?>
<div class="container">
    <h2>Rekap Penjualan per Pelanggan (Agregasi) - PHP Native</h2>
    <p>Jumlah transaksi (<code>COUNT</code>) dan total penjualan (<code>SUM</code>) per pelanggan, memakai <code>GROUP BY</code>.</p>
    <table>
        <thead>
            <tr><th>Kode</th><th>Nama Pelanggan</th><th>Jumlah Transaksi</th><th>Total Penjualan (Rp)</th></tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['kode_pelanggan']) ?></td>
                <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
                <td class="angka"><?= htmlspecialchars($r['jumlah_transaksi']) ?></td>
                <td class="angka">Rp <?= number_format($r['total_penjualan'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
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
    <title>Rekap Penjualan per Pelanggan (Agregasi)</title>
</head>
<body>
<?php require __DIR__ . '/../menu.php'; ?>

<h2>Rekap Penjualan per Pelanggan (Agregasi)</h2>
<p>Jumlah transaksi (COUNT) dan total penjualan (SUM) per pelanggan, memakai GROUP BY.</p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr><th>Kode</th><th>Nama Pelanggan</th><th>Jumlah Transaksi</th><th>Total Penjualan (Rp)</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['kode_pelanggan']) ?></td>
            <td><?= htmlspecialchars($r['nama_pelanggan']) ?></td>
            <td><?= htmlspecialchars($r['jumlah_transaksi']) ?></td>
            <td>Rp <?= number_format($r['total_penjualan'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
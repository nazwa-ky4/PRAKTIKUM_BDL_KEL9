<?php
require_once __DIR__ . '/../koneksi.php';

try {
    $sql = "SELECT pr.kode_produk, pr.nama_produk,
                   COALESCE(SUM(d.jumlah), 0) AS total_unit_terjual,
                   COALESCE(SUM(d.subtotal), 0) AS total_nilai
            FROM produk pr
            LEFT JOIN detil_penjualan d ON d.kode_produk = pr.kode_produk
            GROUP BY pr.kode_produk, pr.nama_produk
            ORDER BY total_unit_terjual DESC, pr.nama_produk ASC";
    $rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gagal mengambil data laporan: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan per Produk (JOIN + Agregasi)</title>
</head>
<body>
<?php require __DIR__ . '/../menu.php'; ?>

<h2>Laporan Penjualan per Produk (JOIN + Agregasi)</h2>
<p>Total unit terjual dan nilai penjualan per produk, diurutkan dari yang paling laris.</p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr><th>Kode</th><th>Nama Produk</th><th>Total Unit Terjual</th><th>Total Nilai (Rp)</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= htmlspecialchars($r['kode_produk']) ?></td>
            <td><?= htmlspecialchars($r['nama_produk']) ?></td>
            <td><?= htmlspecialchars($r['total_unit_terjual']) ?></td>
            <td>Rp <?= number_format($r['total_nilai'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
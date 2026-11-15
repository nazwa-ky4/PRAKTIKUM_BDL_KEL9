<?php
// Memanggil file koneksi.php dari folder utama (naik satu tingkat dengan ../)
require_once '../koneksi.php';

try {
    // Deteksi otomatis kolom harga di tabel detil_penjualan (apakah 'harga' atau lainnya)
    $stmtCol = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'detil_penjualan'");
    $kolom_detil = $stmtCol->fetchAll(PDO::FETCH_COLUMN);

    // Menentukan kolom harga yang valid
    $kolom_harga = 'harga'; // Default umum
    if (in_array('harga_satuan', $kolom_detil)) {
        $kolom_harga = 'harga_satuan';
    } elseif (in_array('harga', $kolom_detil)) {
        $kolom_harga = 'harga';
    }

    // Query dengan penyesuaian kolom harga dinamis
    $sql = "SELECT p.no_penjualan, p.tgl_penjualan, pl.nama_pelanggan, pl.alamat, 
                   COALESCE(SUM(dp.jumlah * dp.$kolom_harga), 0) AS total_transaksi,
                   COUNT(dp.kode_produk) as jenis_item
            FROM penjualan p
            JOIN pelanggan pl ON p.kode_pelanggan = pl.kode_pelanggan
            LEFT JOIN detil_penjualan dp ON p.no_penjualan = dp.no_penjualan
            GROUP BY p.no_penjualan, p.tgl_penjualan, pl.nama_pelanggan, pl.alamat
            ORDER BY p.tgl_penjualan DESC";

    $stmt = $pdo->query($sql);
    $laporan = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Gagal mengambil data laporan: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Backend Logic</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-kembali { display: inline-block; margin-bottom: 15px; text-decoration: none; color: #007bff; }
    </style>
</head>
<body>

    <a href="../index.php" class="btn-kembali">&larr; Kembali ke Menu Produk</a>
    <h2>Laporan Rekapitulasi Penjualan (JOIN & Agregasi)</h2>
    <p>Halaman ini menampilkan data transaksi penjualan yang digabungkan dari tabel <code>penjualan</code>, <code>pelanggan</code>, dan perhitungan total menggunakan fungsi agregasi SQL.</p>

    <table>
        <thead>
            <tr>
                <th>No. Penjualan</th>
                <th>Tanggal</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Jenis Item Produk</th>
                <th>Total Transaksi (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($laporan) > 0): ?>
                <?php foreach ($laporan as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['no_penjualan']) ?></td>
                    <td><?= htmlspecialchars($row['tgl_penjualan']) ?></td>
                    <td><?= htmlspecialchars($row['nama_pelanggan']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td style="text-align: center;"><?= htmlspecialchars($row['jenis_item']) ?> jenis</td>
                    <td>Rp <?= number_format($row['total_transaksi'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Belum ada data transaksi penjualan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
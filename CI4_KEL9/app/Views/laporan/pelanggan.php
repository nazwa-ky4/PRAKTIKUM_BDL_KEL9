<?php // View C.3: hasil agregasi per pelanggan ($rows dari rekapPelanggan). ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2><?= esc($title) ?></h2>
<p>Jumlah transaksi (COUNT) dan total penjualan (SUM) per pelanggan, memakai GROUP BY.</p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr><th>Kode</th><th>Nama Pelanggan</th><th>Jumlah Transaksi</th><th>Total Penjualan (Rp)</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= esc($r['kode_pelanggan']) ?></td>
            <td><?= esc($r['nama_pelanggan']) ?></td>
            <td><?= esc($r['jumlah_transaksi']) ?></td>
            <td>Rp <?= number_format($r['total_penjualan'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
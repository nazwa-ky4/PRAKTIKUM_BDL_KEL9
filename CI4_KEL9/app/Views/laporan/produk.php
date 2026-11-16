<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2><?= esc($title) ?></h2>
<p>Total unit terjual dan nilai penjualan per produk, diurutkan dari yang paling laris.</p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr><th>Kode</th><th>Nama Produk</th><th>Total Unit Terjual</th><th>Total Nilai (Rp)</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= esc($r['kode_produk']) ?></td>
            <td><?= esc($r['nama_produk']) ?></td>
            <td><?= esc($r['total_unit_terjual']) ?></td>
            <td>Rp <?= number_format($r['total_nilai'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
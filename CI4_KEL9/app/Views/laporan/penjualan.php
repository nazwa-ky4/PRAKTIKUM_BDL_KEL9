<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2><?= esc($title) ?></h2>
<p>Gabungan tabel penjualan dan pelanggan dengan JOIN.</p>

<table border="1" cellpadding="6" cellspacing="0">
    <thead>
        <tr><th>No. Penjualan</th><th>Tanggal</th><th>Nama Pelanggan</th><th>Total (Rp)</th></tr>
    </thead>
    <tbody>
    <?php if (! empty($rows)): ?>
        <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= esc($r['no_penjualan']) ?></td>
            <td><?= esc($r['tgl_penjualan']) ?></td>
            <td><?= esc($r['nama_pelanggan']) ?></td>
            <td>Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4">Belum ada transaksi.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2><?= esc($title) ?></h2>
<p>Gabungan tabel <code>penjualan</code> dan <code>pelanggan</code> dengan <code>JOIN</code>.</p>
<table>
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
            <td class="angka">Rp <?= number_format($r['total'], 0, ',', '.') ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="4" style="text-align:center;">Belum ada transaksi.</td></tr>
    <?php endif; ?>
    </tbody>
</table>
<h4>SQL yang dijalankan CodeIgniter</h4>
<pre class="sql"><?= esc($sql) ?></pre>
<?= $this->endSection() ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2><?= esc($title) ?></h2>
<p>Jumlah transaksi (<code>COUNT</code>) dan total penjualan (<code>SUM</code>) per pelanggan, memakai <code>GROUP BY</code>.</p>
<table>
    <thead>
        <tr><th>Kode</th><th>Nama Pelanggan</th><th>Jumlah Transaksi</th><th>Total Penjualan (Rp)</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= esc($r['kode_pelanggan']) ?></td>
            <td><?= esc($r['nama_pelanggan']) ?></td>
            <td class="angka"><?= esc($r['jumlah_transaksi']) ?></td>
            <td class="angka">Rp <?= number_format($r['total_penjualan'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<h4>SQL yang dijalankan CodeIgniter</h4>
<pre class="sql"><?= esc($sql) ?></pre>
<?= $this->endSection() ?>
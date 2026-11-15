<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2><?= esc($title) ?></h2>
<p>Total unit terjual dan nilai penjualan per produk, diurutkan dari yang paling laris.</p>
<table>
    <thead>
        <tr><th>Kode</th><th>Nama Produk</th><th>Total Unit Terjual</th><th>Total Nilai (Rp)</th></tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
        <tr>
            <td><?= esc($r['kode_produk']) ?></td>
            <td><?= esc($r['nama_produk']) ?></td>
            <td class="angka"><?= esc($r['total_unit_terjual']) ?></td>
            <td class="angka">Rp <?= number_format($r['total_nilai'], 0, ',', '.') ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<h4>SQL yang dijalankan CodeIgniter</h4>
<pre class="sql"><?= esc($sql) ?></pre>
<?= $this->endSection() ?>
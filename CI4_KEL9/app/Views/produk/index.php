<?php // View daftar produk + pencarian. Data: $produk, $keyword. ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2>Data Produk — CodeIgniter 4</h2>

<?php // Form cari (GET -> ?keyword=). ?>
<form action="/produk" method="get" class="baris-aksi">
    <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari nama produk...">
    <button type="submit">Cari</button>
    <a href="/produk/create" class="btn btn-tambah">+ Tambah Produk</a>
    <?php if (! empty($keyword)): ?>
        <a href="/produk" class="btn btn-reset">Reset</a>
    <?php endif; ?>
</form>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama</th>
            <th>Satuan</th>
            <th>Harga Jual</th>
            <th>Harga Beli</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    <?php if (! empty($produk)): ?>
        <?php foreach ($produk as $p): ?>
        <tr>
            <td><?= esc($p['kode_produk']) ?></td>
            <td><?= esc($p['nama_produk']) ?></td>
            <td><?= esc($p['satuan']) ?></td>
            <?php // number_format = format Rupiah. ?>
            <td>Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
            <td>Rp <?= number_format($p['harga_beli'], 0, ',', '.') ?></td>
            <td><?= esc($p['stok']) ?></td>
            <td>
                <?php // Ubah -> edit(), Hapus -> delete() (ada konfirmasi). ?>
                <a href="/produk/edit/<?= esc($p['kode_produk']) ?>" class="btn btn-ubah">Ubah</a>
                <a href="/produk/delete/<?= esc($p['kode_produk']) ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="7">Tidak ada data produk ditemukan.</td></tr>
    <?php endif; ?>
    </tbody>
</table>

<?= $this->endSection() ?>
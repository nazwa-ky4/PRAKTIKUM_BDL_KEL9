<?php // Form tambah -> POST /produk/store. old() = isi ulang form jika validasi gagal. ?>
<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2>Tambah Produk Baru</h2>

<form action="/produk/store" method="post">
    <?php // Token CSRF (belum diverifikasi karena filter csrf belum aktif). ?>
    <?= csrf_field() ?>
    <p><label>Kode Produk (Contoh: P006):</label><br>
        <input type="text" name="kode_produk" value="<?= esc(old('kode_produk')) ?>"></p>
    <p><label>Nama Produk:</label><br>
        <input type="text" name="nama_produk" value="<?= esc(old('nama_produk')) ?>"></p>
    <p><label>Satuan (Contoh: buah, rim, botol):</label><br>
        <input type="text" name="satuan" value="<?= esc(old('satuan')) ?>"></p>
    <p><label>Harga Jual (Rp):</label><br>
        <input type="number" name="harga_jual" step="0.01" value="<?= esc(old('harga_jual')) ?>"></p>
    <p><label>Harga Beli (Rp):</label><br>
        <input type="number" name="harga_beli" step="0.01" value="<?= esc(old('harga_beli')) ?>"></p>
    <p><label>Stok:</label><br>
        <input type="number" name="stok" value="<?= esc(old('stok')) ?>"></p>
    <p><label>Deskripsi (Opsional):</label><br>
        <textarea name="deskripsi" rows="3" cols="40"><?= esc(old('deskripsi')) ?></textarea></p>

    <button type="submit">Simpan Produk</button>
    <a href="/produk">Kembali</a>
</form>

<?= $this->endSection() ?>
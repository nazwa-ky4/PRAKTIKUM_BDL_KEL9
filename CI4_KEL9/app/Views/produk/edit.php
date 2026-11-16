<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<h2>Edit Produk: <?= esc($produk['kode_produk']) ?></h2>

<form action="/produk/update/<?= esc($produk['kode_produk']) ?>" method="post">
    <?= csrf_field() ?>
    <p><label>Kode Produk (Tidak dapat diubah):</label><br>
        <input type="text" value="<?= esc($produk['kode_produk']) ?>" disabled></p>
    <p><label>Nama Produk:</label><br>
        <input type="text" name="nama_produk" value="<?= esc(old('nama_produk', $produk['nama_produk'])) ?>"></p>
    <p><label>Satuan:</label><br>
        <input type="text" name="satuan" value="<?= esc(old('satuan', $produk['satuan'])) ?>"></p>
    <p><label>Harga Jual (Rp):</label><br>
        <input type="number" name="harga_jual" step="0.01" value="<?= esc(old('harga_jual', $produk['harga_jual'])) ?>"></p>
    <p><label>Harga Beli (Rp):</label><br>
        <input type="number" name="harga_beli" step="0.01" value="<?= esc(old('harga_beli', $produk['harga_beli'])) ?>"></p>
    <p><label>Stok:</label><br>
        <input type="number" name="stok" value="<?= esc(old('stok', $produk['stok'])) ?>"></p>
    <p><label>Deskripsi:</label><br>
        <textarea name="deskripsi" rows="3" cols="40"><?= esc(old('deskripsi', $produk['deskripsi'])) ?></textarea></p>

    <button type="submit">Simpan Perubahan</button>
    <a href="/produk">Kembali</a>
</form>

<?= $this->endSection() ?>
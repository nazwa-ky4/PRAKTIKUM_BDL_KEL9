<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<h2><?= esc($title) ?></h2>
<p>Satu header penjualan dan minimal dua baris detail disimpan dalam satu transaksi database.</p>
<form action="/transaksi/simpan" method="post">
    <?= csrf_field() ?>
    <p>
        <label>No. Penjualan (maks. 15 karakter):</label><br>
        <input type="text" name="no_penjualan" value="<?= old('no_penjualan') ?>">
    </p>
    <p>
        <label>Tanggal Penjualan:</label><br>
        <input type="date" name="tgl_penjualan" value="<?= old('tgl_penjualan', date('Y-m-d')) ?>">
    </p>
    <p>
        <label>Pelanggan:</label><br>
        <select name="kode_pelanggan">
            <option value="">-- Pilih Pelanggan --</option>
            <?php foreach ($pelanggan as $pl): ?>
                <option value="<?= esc($pl['kode_pelanggan']) ?>" <?= old('kode_pelanggan') === $pl['kode_pelanggan'] ? 'selected' : '' ?>>
                    <?= esc($pl['nama_pelanggan']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>
        <label>Keterangan:</label><br>
        <input type="text" name="keterangan" value="<?= old('keterangan') ?>">
    </p>
    <h4>Detail Penjualan (isi minimal 2 baris)</h4>
    <datalist id="daftar-produk">
        <?php foreach ($produk as $pr): ?>
            <option value="<?= esc($pr['kode_produk']) ?>"><?= esc($pr['nama_produk']) ?></option>
        <?php endforeach; ?>
    </datalist>
    <table>
        <thead>
            <tr><th>No</th><th>Kode Produk</th><th>Jumlah</th><th>Harga (Rp)</th></tr>
        </thead>
        <tbody>
        <?php for ($i = 0; $i < 3; $i++): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><input type="text" name="detail[<?= $i ?>][kode_produk]" list="daftar-produk" value="<?= old("detail.$i.kode_produk") ?>"></td>
                <td><input type="number" name="detail[<?= $i ?>][jumlah]" min="1" value="<?= old("detail.$i.jumlah") ?>"></td>
                <td><input type="number" name="detail[<?= $i ?>][harga]" min="0" step="0.01" value="<?= old("detail.$i.harga") ?>"></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>
    <p><button type="submit" class="btn">Simpan Transaksi</button></p>
</form>
<?= $this->endSection() ?>
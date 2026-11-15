<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title) ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f6f9; }
        .form-container { background: #fff; padding: 20px; border-radius: 6px; max-width: 500px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        textarea { height: 80px; resize: vertical; }
        .btn-submit { background: #ffc107; color: #333; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-weight: bold; }
        .btn-kembali { background: #6c757d; color: white; padding: 10px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-left: 10px; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Edit Produk: <?= esc($produk['kode_produk']) ?></h2>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert-error">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <div><?= esc($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <form action="/produk/update/<?= esc($produk['kode_produk']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label>Kode Produk (Tidak dapat diubah):</label>
            <input type="text" value="<?= esc($produk['kode_produk']) ?>" disabled style="background:#e9ecef;">
        </div>
        <div class="form-group">
            <label>Nama Produk:</label>
            <input type="text" name="nama_produk" value="<?= esc(old('nama_produk', $produk['nama_produk'])) ?>">
        </div>
        <div class="form-group">
            <label>Satuan:</label>
            <input type="text" name="satuan" value="<?= esc(old('satuan', $produk['satuan'])) ?>">
        </div>
        <div class="form-group">
            <label>Harga Jual (Rp):</label>
            <input type="number" name="harga_jual" step="0.01" value="<?= esc(old('harga_jual', $produk['harga_jual'])) ?>">
        </div>
        <div class="form-group">
            <label>Harga Beli (Rp):</label>
            <input type="number" name="harga_beli" step="0.01" value="<?= esc(old('harga_beli', $produk['harga_beli'])) ?>">
        </div>
        <div class="form-group">
            <label>Stok:</label>
            <input type="number" name="stok" value="<?= esc(old('stok', $produk['stok'])) ?>">
        </div>
        <div class="form-group">
            <label>Deskripsi:</label>
            <textarea name="deskripsi"><?= esc(old('deskripsi', $produk['deskripsi'])) ?></textarea>
        </div>
        <button type="submit" class="btn-submit">Simpan Perubahan</button>
        <a href="/produk" class="btn-kembali">Kembali</a>
    </form>
</div>
</body>
</html>
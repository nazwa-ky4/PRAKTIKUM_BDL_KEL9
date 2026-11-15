<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f6f9; }
        .container { background: white; padding: 20px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #007bff; color: white; }
        .btn { padding: 6px 12px; text-decoration: none; border-radius: 4px; color: white; font-size: 14px; }
        .btn-primary { background: #007bff; }
        .btn-danger { background: #dc3545; }
        .btn-warning { background: #ffc107; color: black; }
        form { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Data Produk - CodeIgniter 4 (DB: db_penjualan)</h2>
        
        <a href="/produk/create" class="btn btn-primary">+ Tambah Produk Baru</a>
        
        <form action="" method="get" style="margin-top: 15px;">
            <input type="text" name="keyword" value="<?= esc($keyword ?? '') ?>" placeholder="Cari nama produk...">
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga Jual (Rp)</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($produk)): ?>
                    <?php foreach ($produk as $p): ?>
                    <tr>
                        <td><?= esc($p['kode_produk']) ?></td>
                        <td><?= esc($p['nama_produk']) ?></td>
                        <td>Rp <?= number_format($p['harga_jual'], 0, ',', '.') ?></td>
                        <td><?= esc($p['stok']) ?></td>
                        <td>
                            <a href="/produk/edit/<?= $p['kode_produk'] ?>" class="btn btn-warning">Edit</a>
                            <a href="/produk/delete/<?= $p['kode_produk'] ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5" style="text-align: center;">Belum ada data produk.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
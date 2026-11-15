<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Laporan') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 30px; background: #f4f6f9; }
        nav a { display: inline-block; margin-right: 8px; padding: 8px 14px; background: #343a40; color: #fff; text-decoration: none; border-radius: 4px; }
        nav a:hover { background: #007bff; }
        .container { background: #fff; padding: 20px; margin-top: 15px; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #007bff; color: #fff; }
        td.angka { text-align: right; }
        pre.sql { background: #272822; color: #f8f8f2; padding: 12px; border-radius: 4px; overflow-x: auto; font-size: 13px; }
    </style>
</head>
<body>
    <nav>
        <a href="/produk">Data Produk</a>
        <a href="/laporan/penjualan">Transaksi Penjualan</a>
        <a href="/laporan/pelanggan">Rekap Pelanggan</a>
        <a href="/laporan/produk">Rekap Produk</a>
        <a href="/transaksi/tambah">Input Transaksi</a>
    </nav>
    <div class="container">
        <?php if (session()->getFlashdata('pesan')): ?>
            <div style="background:#d4edda;color:#155724;padding:10px;margin-bottom:10px;border-radius:4px;"><?= esc(session()->getFlashdata('pesan')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('galat')): ?>
            <div style="background:#f8d7da;color:#721c24;padding:10px;margin-bottom:10px;border-radius:4px;"><?= esc(session()->getFlashdata('galat')) ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div style="background:#f8d7da;color:#721c24;padding:10px;margin-bottom:10px;border-radius:4px;">
                <?php foreach (session()->getFlashdata('errors') as $e): ?>
                    <div><?= esc($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
</body>
</html>
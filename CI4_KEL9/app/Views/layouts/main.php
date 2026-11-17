<?php // Layout induk: CSS, menu, pesan, dan tempat isi halaman. esc() = cegah XSS. ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($title ?? 'Aplikasi') ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 16px; margin: 0; padding: 20px 24px 40px; background: #f5f7fb; color: #1f2937; }

        nav { display: flex; flex-wrap: wrap; align-items: center; gap: 4px; padding: 12px 18px; background: #ffffff; border: 1px solid #dfe4ee; border-top: 4px solid #2563eb; border-radius: 8px; margin-bottom: 22px; }
        nav .merek { font-weight: bold; color: #1e3a8a; margin-right: 16px; }
        nav a { padding: 8px 14px; color: #2563eb; text-decoration: none; border-radius: 6px; }
        nav a:hover { background: #e8efff; }

        h2 { margin: 0 0 18px; color: #1e3a8a; font-size: 26px; }
        a { color: #2563eb; }

        table { width: 100%; border-collapse: collapse; margin-top: 18px; background: #ffffff; }
        th, td { border: 1px solid #e3e8f0; padding: 16px 14px; text-align: left; }
        th { background: #e8efff; color: #1e3a8a; }
        tbody tr:nth-child(even) { background: #f9fbff; }
        tbody tr:hover { background: #eef4ff; }

        input[type="text"], input[type="number"], input[type="date"], select, textarea { padding: 10px 12px; border: 1px solid #c7d0e0; border-radius: 6px; font-size: 15px; font-family: inherit; background: #ffffff; min-width: 320px; }
        td input[type="text"], td input[type="number"] { width: 100%; min-width: 0; }
        input:disabled { background: #eef1f6; color: #6b7280; }
        label { font-weight: bold; }

        button, .btn { display: inline-block; padding: 10px 20px; border: 1px solid #2563eb; background: #2563eb; color: #ffffff; border-radius: 6px; font-size: 15px; font-family: inherit; text-decoration: none; cursor: pointer; }
        button:hover, .btn:hover { background: #1d4ed8; }
        .btn-tambah { background: #ffffff; color: #2563eb; }
        .btn-tambah:hover { background: #e8efff; }
        .btn-reset { background: #ffffff; color: #475569; border-color: #cbd5e1; }
        .btn-reset:hover { background: #f1f5f9; }
        .btn-ubah, .btn-hapus { padding: 7px 16px; background: #ffffff; }
        .btn-ubah { color: #2563eb; border-color: #93b4f5; }
        .btn-ubah:hover { background: #e8efff; }
        .btn-hapus { color: #dc2626; border-color: #f1a5a5; }
        .btn-hapus:hover { background: #fee2e2; }

        .baris-aksi { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; }
        .sukses { background: #dcfce7; color: #166534; border: 1px solid #86efac; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
        .galat  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 12px 16px; border-radius: 6px; margin-bottom: 16px; }
    </style>
</head>
<body>
    <nav>
        <span class="merek">[CODEIGNITER 4]</span>
        <a href="/produk">Produk</a>
        <a href="/laporan/penjualan">Penjualan</a>
        <a href="/laporan/pelanggan">Agregasi</a>
        <a href="/laporan/produk">Laporan</a>
        <a href="/transaksi/tambah">Transaksi Baru</a>
    </nav>

    <?php // Pesan sekali tampil dari Controller: pesan (sukses), galat (error/ROLLBACK), errors (validasi). ?>
    <?php if (session()->getFlashdata('pesan')): ?>
        <div class="sukses"><?= esc(session()->getFlashdata('pesan')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('galat')): ?>
        <div class="galat"><?= esc(session()->getFlashdata('galat')) ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="galat">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <div><?= esc($e) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php // Isi tiap halaman disisipkan di sini. ?>
    <?= $this->renderSection('content') ?>
</body>
</html>
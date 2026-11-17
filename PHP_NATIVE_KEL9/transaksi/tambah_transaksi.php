<?php
// C.5 Transaksi database: simpan 1 header + banyak detail. COMMIT jika semua sukses, ROLLBACK jika ada yang gagal.
require_once __DIR__ . '/../koneksi.php';

// Fungsi bantu: h() = htmlspecialchars, lama()/lamaDetail() = isi ulang form.
function h($teks) { return htmlspecialchars((string) $teks); }
function lama($nama, $default = '') { return h($_POST[$nama] ?? $default); }
function lamaDetail($i, $kolom) { return h($_POST['detail'][$i][$kolom] ?? ''); }

$error = '';

// Data referensi untuk pilihan di form
try {
    $list_pelanggan = $pdo->query("SELECT * FROM pelanggan ORDER BY nama_pelanggan ASC")->fetchAll(PDO::FETCH_ASSOC);
    $list_produk    = $pdo->query("SELECT * FROM produk ORDER BY kode_produk ASC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Gagal memuat data referensi: " . $e->getMessage());
}

// Hanya jalan saat form dikirim.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_penjualan   = trim($_POST['no_penjualan'] ?? '');
    $tgl_penjualan  = trim($_POST['tgl_penjualan'] ?? '');
    $kode_pelanggan = trim($_POST['kode_pelanggan'] ?? '');
    $keterangan     = trim($_POST['keterangan'] ?? '');

    // Kumpulkan baris detail (baris kosong dilewati)
    $detail = [];
    $total  = 0;

    foreach (($_POST['detail'] ?? []) as $b) {
        $kode   = trim($b['kode_produk'] ?? '');
        $jumlah = trim($b['jumlah'] ?? '');
        $harga  = trim($b['harga'] ?? '');

        if ($kode === '' && $jumlah === '' && $harga === '') {
            continue;
        }
        if ($kode === '' || !ctype_digit($jumlah) || (int) $jumlah <= 0
            || !is_numeric($harga) || (float) $harga < 0) {
            $error = "Setiap baris detail harus berisi kode produk, jumlah (> 0), dan harga (>= 0).";
            break;
        }

        // Subtotal & total dihitung di server.
        $subtotal = (int) $jumlah * (float) $harga;
        $total   += $subtotal;
        $detail[] = [
            'kode_produk' => $kode,
            'jumlah'      => (int) $jumlah,
            'harga'       => (float) $harga,
            'subtotal'    => $subtotal,
        ];
    }

    // Validasi header dan jumlah detail
    if ($error === '') {
        if ($no_penjualan === '' || $tgl_penjualan === '' || $kode_pelanggan === '') {
            $error = "No. penjualan, tanggal, dan pelanggan wajib diisi.";
        } elseif (strlen($no_penjualan) > 15) {
            $error = "No. penjualan maksimal 15 karakter.";
        } elseif (count($detail) < 2) {
            // Minimal 2 detail. Dicek sebelum transaksi, jadi ini validasi (bukan rollback).
            $error = "Transaksi harus memiliki minimal dua baris detail.";
        }
    }

    // Simpan dalam satu transaksi database
    if ($error === '') {
        try {
            // 1. Mulai transaksi
            $pdo->beginTransaction();

            // 2. Simpan header penjualan
            $stmtHeader = $pdo->prepare(
                "INSERT INTO penjualan (no_penjualan, tgl_penjualan, kode_pelanggan, total, keterangan)
                 VALUES (?, ?, ?, ?, ?)"
            );
            // Gagal (mis. no_penjualan ganda / angka terlalu besar) -> exception -> catch -> ROLLBACK.
            $stmtHeader->execute([$no_penjualan, $tgl_penjualan, $kode_pelanggan, $total, $keterangan]);

            // 3. Simpan seluruh detail penjualan
            $stmtDetil = $pdo->prepare(
                "INSERT INTO detil_penjualan (no_penjualan, no_urut, kode_produk, jumlah, harga, subtotal)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            // 1 INSERT per detail. Kode produk tidak ada (mis. P999) -> foreign key error -> ROLLBACK.
            foreach ($detail as $i => $d) {
                $stmtDetil->execute([
                    $no_penjualan, $i + 1, $d['kode_produk'], $d['jumlah'], $d['harga'], $d['subtotal'],
                ]);
            }

            // 4. Semua berhasil -> COMMIT
            $pdo->commit();

            header("Location: ../laporan/penjualan.php?sukses=" . urlencode($no_penjualan));
            exit;

        } catch (Exception $e) {
            // 5. Ada yang gagal -> ROLLBACK
            if ($pdo->inTransaction()) {
                // Batalkan semua INSERT sejak beginTransaction().
                $pdo->rollBack();
            }
            $error = "Transaksi GAGAL dan di-ROLLBACK. Tidak ada data yang tersimpan. Penyebab: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Input Transaksi Penjualan</title>
</head>
<body>
<?php require __DIR__ . '/../menu.php'; ?>

<h2>Input Transaksi Penjualan</h2>
<p>Satu header penjualan dan minimal dua baris detail disimpan dalam satu transaksi database.</p>

<?php // Pesan error (validasi atau ROLLBACK). ?>
<?php if ($error !== ''): ?>
    <p><strong>[GAGAL]</strong> <?= h($error) ?></p>
<?php endif; ?>

<form action="" method="POST">
    <p><label>No. Penjualan (maks. 15 karakter):</label><br>
        <input type="text" name="no_penjualan" value="<?= lama('no_penjualan') ?>"></p>
    <p><label>Tanggal Penjualan:</label><br>
        <input type="date" name="tgl_penjualan" value="<?= lama('tgl_penjualan', date('Y-m-d')) ?>"></p>
    <p><label>Pelanggan:</label><br>
        <select name="kode_pelanggan">
            <option value="">-- Pilih Pelanggan --</option>
            <?php foreach ($list_pelanggan as $pl): ?>
                <option value="<?= h($pl['kode_pelanggan']) ?>" <?= (($_POST['kode_pelanggan'] ?? '') === $pl['kode_pelanggan']) ? 'selected' : '' ?>>
                    <?= h($pl['nama_pelanggan']) ?>
                </option>
            <?php endforeach; ?>
        </select></p>
    <p><label>Keterangan:</label><br>
        <input type="text" name="keterangan" value="<?= lama('keterangan') ?>"></p>

    <h4>Detail Penjualan (isi minimal 2 baris)</h4>

    <?php // Saran kode produk; boleh ketik bebas (mis. P999) untuk uji ROLLBACK. ?>
    <datalist id="daftar-produk">
        <?php foreach ($list_produk as $pr): ?>
            <option value="<?= h($pr['kode_produk']) ?>"><?= h($pr['nama_produk']) ?></option>
        <?php endforeach; ?>
    </datalist>

    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr><th>No</th><th>Kode Produk</th><th>Jumlah</th><th>Harga (Rp)</th></tr>
        </thead>
        <tbody>
        <?php // 3 baris detail; name=detail[i][kolom] menjadi array $_POST['detail']. ?>
        <?php for ($i = 0; $i < 3; $i++): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><input type="text" name="detail[<?= $i ?>][kode_produk]" list="daftar-produk" value="<?= lamaDetail($i, 'kode_produk') ?>"></td>
                <td><input type="number" name="detail[<?= $i ?>][jumlah]" min="1" value="<?= lamaDetail($i, 'jumlah') ?>"></td>
                <td><input type="number" name="detail[<?= $i ?>][harga]" min="0" step="0.01" value="<?= lamaDetail($i, 'harga') ?>"></td>
            </tr>
        <?php endfor; ?>
        </tbody>
    </table>

    <p><button type="submit">Proses Transaksi</button></p>
</form>

</body>
</html>
<?php

namespace App\Controllers;

use App\Models\LaporanModel;

// Controller laporan: ambil data dari LaporanModel, kirim ke View (tanpa SQL).
class LaporanController extends BaseController
{
    protected LaporanModel $laporan;

    public function __construct()
    {
        $this->laporan = new LaporanModel();
    }

    // C.2 JOIN penjualan + pelanggan.
    public function penjualan()
    {
        $rows = $this->laporan->daftarPenjualan();
        // Array kedua di view() menjadi variabel di View.
        return view('laporan/penjualan', [
            'title' => 'Daftar Transaksi Penjualan (JOIN)',
            'rows'  => $rows,
            // SQL terakhir yang dijalankan (untuk menunjukkan hasil Query Builder).
            'sql'   => (string) db_connect()->getLastQuery(),
        ]);
    }

    // C.3 Agregasi COUNT/SUM per pelanggan.
    public function pelanggan()
    {
        $rows = $this->laporan->rekapPelanggan();
        return view('laporan/pelanggan', [
            'title' => 'Rekap Penjualan per Pelanggan (Agregasi)',
            'rows'  => $rows,
            'sql'   => (string) db_connect()->getLastQuery(),
        ]);
    }

    // C.4 Total qty & nilai per produk.
    public function produk()
    {
        $rows = $this->laporan->rekapProduk();
        return view('laporan/produk', [
            'title' => 'Laporan Penjualan per Produk (JOIN + Agregasi)',
            'rows'  => $rows,
            'sql'   => (string) db_connect()->getLastQuery(),
        ]);
    }
}